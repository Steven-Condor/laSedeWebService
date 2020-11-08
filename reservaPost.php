<?php

    include "config.php";
    include "utils.php";

    $dbConn = connect($db);

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (isset($_GET['id_reserva'])) {
            //Mostrar post
            $sql = $dbConn->prepare("SELECT * FROM reserva WHERE id_reserva =: id_reserva");
            $sql->bindValue(':id_reserva',$_GET['id_reserva']);
            $sql->execute();
            header("HTTP/1.1 200 OK");

            echo json_encode($sql->fetch(PDO::FETCH_ASSOC));

            exit();
        }else{
            //Mostar lista de post
            $sql = $dbConn->prepare("SELECT * FROM reserva");
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
        $sql = "INSERT INTO reserva
            (fecha,observacion,id_usuario,id_cancha_hora, estado)
            VALUES
            (:fecha,:observacion,:id_usuario, :id_cancha_hora,:estado)";
        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);
        $statement->execute();

        $postCodigo = $dbConn->lastInsertId();

        if($postCodigo)
        {
            $input['id_reserva'] = $postCodigo;
            header("HTTP/1.1 200 OK");
            echo json_encode($input);
            exit();
        }
        
    }

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
    {
        $codigo = $_GET['id_reserva'];
        $statement = $dbConn->prepare("DELETE FROM  reserva where id_reserva=:id_reserva");
        $statement->bindValue(':id_reserva', $codigo);
        $statement->execute();
            header("HTTP/1.1 200 OK");
            exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT')
    {
        $input = $_GET;
        $postCodigo = $input['id_reserva'];
        $fields = getParams($input);

        $sql = "
            UPDATE reserva
            SET $fields
            WHERE id_reserva ='$postCodigo'
            ";

        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);

        $statement->execute();
        header("HTTP/1.1 200 OK");
        exit();
    }

?>