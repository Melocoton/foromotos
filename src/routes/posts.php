<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/posts', function (Request $request, Response $response, array $args) {
    $idtema = $request->getParam("idtema");

    $stmt = $this->db->prepare("SELECT *, (SELECT u.nombre FROM usuario u WHERE u.idusuario = p.idusuario) AS nombre FROM post p WHERE idtema = :idtema");
    $stmt->bindParam('idtema', $idtema);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();

    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($result));
});

$app->get('/cuenta', function (Request $request, Response $response, array $args) {
    $idtema = $request->getParam("idtema");

    $stmt = $this->db->prepare("SELECT COUNT(idpost) AS cuenta FROM post WHERE idtema = :idtema");
    $stmt->bindParam('idtema', $idtema);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();
    $row = $result[0];
    $cuenta = intval($row["cuenta"]);

    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($cuenta));
});

$app->post('/posts', function (Request $request, Response $response, array $args) {
    if (isset($_SESSION['login'])){
        $idtema = $request->getParam("idtema");
        $contenido = $request->getParam("contenido");

        $stmt = $this->db->prepare("INSERT INTO `post` (`idpost`, `contenido`, `fecha_creacion`, `idtema`, `idusuario`) 
            VALUES (NULL, :contenido, CURRENT_TIMESTAMP, :idtema, :idusuario)");
        $stmt->bindParam('idtema', $idtema);
        $stmt->bindParam('contenido', $contenido);
        $stmt->bindParam('idusuario', $_SESSION["idusuario"]);
        $stmt->execute();

        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => true, "msg" => "Post Creado"]));

    }

    return $response->withStatus(403)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => false, "msg" => "Esta accion requiere estar logueado"]));
});

$app->post('/posts/eliminar', function (Request $request, Response $response, array $args) {
    if (isset($_SESSION['login'])){
        $idpost = $request->getParam("idpost");

        $stmt = $this->db->prepare("DELETE FROM post WHERE idpost = :ipost AND idusuario = :idusuario");
        $stmt->bindParam('idpost', $idpost);
        $stmt->bindParam('idusuario', $_SESSION["idusuario"]);
        $stmt->execute();

        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => true, "msg" => "Post eliminado"]));

    }

    return $response->withStatus(403)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => false, "msg" => "Esta accion requiere estar logueado"]));
});