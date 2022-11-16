<?php

function pdo_connect_bdd(){
    try {
        $database_host = 'localhost';
        $database_name = 'spa';
        $database_pass = '';
        $database_user = 'root';

        $conn = new PDO('mysql:host=' . $database_host . ';dbname=' . $database_name . ';charset=utf8', $database_user, $database_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;

    } catch (PDOException $exception) {
        // S'il y a une erreur de connexion, arrêtez le script et affichez le message erreur.
        echo "Connection failed: " . $exception->getMessage();
    }
}