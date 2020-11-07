<?php

    include "config.php";
    include "utils.php";

    $dbConn = connect($db);

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (isset($_GET['id_tipo_cancha'])) {
            //Mostrar post
            $sql = $dbConn->prepare("SELECT * FROM tipo_cancha WHERE id_tipo_cancha =: id_tipo_cancha");
            $sql->bindValue(':id_tipo_cancha',$_GET['id_tipo_cancha']);
            $sql->execute();
            header("HTTP/1.1 200 OK");

            echo json_encode($sql->fetch(PDO::FETCH_ASSOC));

            exit();
        }else{
            //Mostar lista de post
            $sql = $dbConn->prepare("SELECT * FROM tipo_cancha");
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
        $sql = "INSERT INTO tipo_cancha
            (nombre, estado)
            VALUES
            (:nombre, :estado)";
        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);
        $statement->execute();

        $postCodigo = $dbConn->lastInsertId();

        if($postCodigo)
        {
            $input['id_tipo_cancha'] = $postCodigo;
            header("HTTP/1.1 200 OK");
            echo json_encode($input);
            exit();
        }
        
    }

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
        $codigo = $_GET['id_tipo_cancha'];
        $statement = $dbConn->prepare("DELETE FROM  tipo_cancha where id_tipo_cancha=:id_tipo_cancha");
        $statement->bindValue(':id_tipo_cancha', $codigo);
        $statement->execute();
            header("HTTP/1.1 200 OK");
            exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT')
    {
        $input = $_GET;
        $postCodigo = $input['id_tipo_cancha'];
        $fields = getParams($input);

        $sql = "
            UPDATE tipo_cancha
            SET $fields
            WHERE id_tipo_cancha='$postCodigo'
            ";

        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);

        $statement->execute();
        header("HTTP/1.1 200 OK");
        exit();
    }

?>