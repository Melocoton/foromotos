<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/login', function (Request $request, Response $response, array $args) {

    $user = $request->getParam("correo");
    $clave = $request->getParam("clave");

    $stmt = $this->db->prepare("SELECT * FROM usuario WHERE correo = :user");
    $stmt->bindParam('user', $user);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();

    //si el usuario existe
    if (!empty($result)){
        $row = $result[0];
    }else{
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => false, "msg" => "El usuario no existe"]));
    }

    //si la contraseña coincide
    if (password_verify($clave, $row["clave"])){
        $_SESSION["login"]=true;
        $_SESSION["idusuario"] = $row["idusuario"];

        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => true, "msg" => "Login correcto"]));
    } else {
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => false, "msg" => "Contraseña incorrecta"]));
    }
});

//#####################################################################################################################

$app->post('/registrar', function (Request $request, Response $response, array $args) {

    $nombre = $request->getParam("nombre");
    $apellidos = $request->getParam("apellidos");
    $correo = $request->getParam("correo");
    $clave = $request->getParam("clave");
    $fecha_nacimiento = $request->getParam("fecha_nacimiento");
    $sexo = $request->getParam("sexo");

    $stmt = $this->db->prepare("SELECT correo FROM usuario WHERE correo = :correo");
    $stmt->bindParam('correo', $correo);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();

    //si el usuario existe
    if (!empty($result)){
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => false, "msg" => "El usuario ya existe existe, elija otro correo"]));
    }

    $clave = password_hash($clave, PASSWORD_BCRYPT);

    $stmt = $this->db->prepare("INSERT INTO `usuario` (`idusuario`, `nombre`, `apellidos`, `clave`, `correo`, `fecha_registro`, `fecha_nacimiento`, `sexo`) 
        VALUES (NULL, :nombre, :apellidos, :clave, :correo, CURRENT_TIMESTAMP, STR_TO_DATE(:fecha_nacimiento, '%d-%m-%Y'), :sexo)");
    $stmt->bindParam('nombre', $nombre);
    $stmt->bindParam('apellidos', $apellidos);
    $stmt->bindParam('clave', $clave);
    $stmt->bindParam('correo', $correo);
    $stmt->bindParam('fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindParam('sexo', $sexo);
    $stmt->execute();

    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(["result" => true, "msg" => "Usuario creado correctamente"]));

});

//#####################################################################################################################

$app->get('/logout', function (Request $request, Response $response, array $args) {
    session_destroy();

    return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode(true));
});
