<?php

    include "config.php";
    include "utils.php";

    $dbConn = connect($db);

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (isset($_GET['id_usuario'])) {
            //Mostrar post
            $sql = $dbConn->prepare("SELECT * FROM usuario WHERE id_usuario =:id_usuario");
            $sql->bindValue(':id_usuario',$_GET['id_usuario']);
            $sql->execute();
            header("HTTP/1.1 200 OK");

            echo json_encode($sql->fetch(PDO::FETCH_ASSOC));

            exit();
        }elseif(isset($_GET['correo'])){
            //Mostrar post
            $sql = $dbConn->prepare("SELECT id_usuario, nombres, apellidos,  correo, password, estado, documento, 
                                     telefono, url_foto,tipo_usuario FROM usuario WHERE correo =:correo OR usuario =:correo");
            $sql->bindValue(':correo',$_GET['correo']);
            $sql->execute();
            header("HTTP/1.1 200 OK");

            echo json_encode($sql->fetch(PDO::FETCH_ASSOC));

            exit();
        }else{
            //Mostar lista de post
            $sql = $dbConn->prepare("SELECT * FROM usuario");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            header("HTTP/1.1 200 OK");
            echo json_encode($sql->fetchAll());
            exit(); 
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $input = $_POST;
        $sql = "INSERT INTO usuario
            (apellidos, nombres, correo, password, estado, tipo_usuario)
            VALUES
            (:apellidos, :nombres, :correo, :password, :estado, :tipo_usuario)";
        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);
        $statement->execute();

        $postCodigo = $dbConn->lastInsertId();

        if($postCodigo)
        {
            $input['id_usuario'] = $postCodigo;
            header("HTTP/1.1 200 OK");
            echo json_encode($input);
            exit();
        }
        
    }

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
        $codigo = $_GET['id_usuario'];
        $statement = $dbConn->prepare("DELETE FROM  usuario where id_usuario=:id_usuario");
        $statement->bindValue(':id_usuario', $codigo);
        $statement->execute();
            header("HTTP/1.1 200 OK");
            exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT')
    {
        $input = $_GET;
        $postCodigo = $input['id_usuario'];
        $fields = getParams($input);

        $sql = "
            UPDATE usuario
            SET $fields
            WHERE id_usuario='$postCodigo'
            ";

        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);

        $statement->execute();
        header("HTTP/1.1 200 OK");
        exit();
    }

?>