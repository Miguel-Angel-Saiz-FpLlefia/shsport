<?php

    $host = "mysql-miguelangelsaiz.alwaysdata.net";
    $username = "439218";
    $password = "Miguel1014";
    $dbName = "miguelangelsaiz_shsport";

    $mysqli = new mysqli($host, $username, $password, $dbName); //Creamos un objeto mysqli con los datos necesarios

    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error); //Mostrar porque la conexion es fallida
    }

    $mysqli->set_charset("utf8mb4"); //Para tener encuenta el metacharset

?>