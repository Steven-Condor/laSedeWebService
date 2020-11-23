<?php

    include "config.php";
    include "utils.php";

    $dbConn = connect($db);

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (isset($_GET['id_horario'])) {
            //Mostrar post
            $sql = $dbConn->prepare("SELECT * FROM horario WHERE id_horario=:id_horario");
            $sql->bindValue(':id_horario',$_GET['id_horario']);
            $sql->execute();
            header("HTTP/1.1 200 OK");

            echo json_encode($sql->fetch(PDO::FETCH_ASSOC));

            exit();
        }else{
            //Mostar lista de post
            $sql = $dbConn->prepare("SELECT * FROM horario");
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
        $sql = "INSERT INTO horario
            (hora_inicio,hora_fin, estado)
            VALUES
            (:hora_inicio,:hora_fin,:estado)";
        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);
        $statement->execute();

        $postCodigo = $dbConn->lastInsertId();

        if($postCodigo)
        {
            $input['id_horario'] = $postCodigo;
            header("HTTP/1.1 200 OK");
            echo json_encode($input);
            exit();
        }
        
    }

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
        $codigo = $_GET['id_horario'];
        $statement = $dbConn->prepare("DELETE FROM  horario where id_horario=:id_horario");
        $statement->bindValue(':id_horario', $codigo);
        $statement->execute();
            header("HTTP/1.1 200 OK");
            exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT')
    {
        $input = $_GET;
        $postCodigo = $input['id_horario'];
        $fields = getParams($input);

        $sql = "
            UPDATE horario
            SET $fields
            WHERE id_horario='$postCodigo'
            ";

        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);

        $statement->execute();
        header("HTTP/1.1 200 OK");
        exit();
    }

?>