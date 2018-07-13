<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/temas', function (Request $request, Response $response, array $args) {
    
    $stmt = $this->db->prepare("SELECT *, (SELECT COUNT(p.idpost) FROM post p WHERE p.idtema = t.idtema) AS npost, (SELECT fecha_creacion FROM post WHERE idtema = t.idtema ORDER BY fecha_creacion DESC LIMIT 1) AS last_post FROM tema t ORDER BY t.fecha_creacion DESC");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();

    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($result));
});

$app->post('/temas', function (Request $request, Response $response, array $args) {
    
    if (isset($_SESSION['login'])){
        $titulo = $request->getParam("titulo");

        $stmt = $this->db->prepare("INSERT INTO `tema` (`idtema`, `titulo`, `fecha_creacion`, `idusuario`) 
            VALUES (NULL, :titulo, CURRENT_TIMESTAMP, :idusuario)");
        $stmt->bindParam('titulo', $titulo);
        $stmt->bindParam('idusuario', $_SESSION["idusuario"]);
        $stmt->execute();

        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => true, "msg" => "Tema añadido"]));

    }

    return $response->withStatus(403)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => false, "msg" => "Esta accion requiere estar logueado"]));
});

$app->post('/temas/eliminar', function (Request $request, Response $response, array $args) {
    
    if (isset($_SESSION['login'])){
        $idtema = $request->getParam("idtema");

        $stmt = $this->db->prepare("DELETE FROM tema WHERE idtema = :idtema AND idusuario = :idusuario");
        $stmt->bindParam('idtema', $idtema);
        $stmt->bindParam('idusuario', $_SESSION["idusuario"]);
        $stmt->execute();

        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => true, "msg" => "Tema eliminado"]));

    }

    return $response->withStatus(403)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => false, "msg" => "Esta accion requiere estar logueado"]));
});