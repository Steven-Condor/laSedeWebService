<?php

    function connect($db)
    {

        try {
            
            $conn = new PDO("mysql:host={$db['host']};dbname={$db['db']}",$db['username'],$db['password']);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;

        } catch (PDOException $ex) {
            
            return($ex->getMessage());

        }
        return 0;
    
    }

    function getParams($input)
    {
        $filter = [];
        foreach ($input as $param => $value) {
            $filterParams[] = "$param=:$param";
        }
        return implode(",", $filterParams);
    }

    function bindAllValues($statement, $params)
    {
        foreach ($params as $param => $value) {
            $statement->bindValue(':'.$param, $value);
        }
        return $statement;
    }

?>
