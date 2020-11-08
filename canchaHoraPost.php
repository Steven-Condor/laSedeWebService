<?php

    include "config.php";
    include "utils.php";

    $dbConn = connect($db);

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (isset($_GET['id_cancha_hora'])) {
            //Mostrar post
            $sql = $dbConn->prepare("SELECT * FROM cancha_hora WHERE id_cancha_hora =: id_cancha_hora");
            $sql->bindValue(':id_cancha_hora',$_GET['id_cancha_hora']);
            $sql->execute();
            header("HTTP/1.1 200 OK");

            echo json_encode($sql->fetch(PDO::FETCH_ASSOC));

            exit();
        }else{
            //Mostar lista de post
            $sql = $dbConn->prepare("SELECT * FROM cancha_hora");
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
        $sql = "INSERT INTO cancha_hora
            (id_cancha,id_horario, estado)
            VALUES
            (:id_cancha, :id_horario,:estado)";
        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);
        $statement->execute();

        $postCodigo = $dbConn->lastInsertId();

        if($postCodigo)
        {
            $input['id_cancha_hora'] = $postCodigo;
            header("HTTP/1.1 200 OK");
            echo json_encode($input);
            exit();
        }
        
    }

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
        $codigo = $_GET['id_cancha_hora'];
        $statement = $dbConn->prepare("DELETE FROM  cancha_hora where id_cancha_hora=:id_cancha_hora");
        $statement->bindValue(':id_cancha_hora', $codigo);
        $statement->execute();
            header("HTTP/1.1 200 OK");
            exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT')
    {
        $input = $_GET;
        $postCodigo = $input['id_cancha_hora'];
        $fields = getParams($input);

        $sql = "
            UPDATE cancha_hora
            SET $fields
            WHERE id_cancha_hora ='$postCodigo'
            ";

        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);

        $statement->execute();
        header("HTTP/1.1 200 OK");
        exit();
    }

?>