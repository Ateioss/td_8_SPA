<?php
include_once ("bdd/bdd-pdo.php");

// je démarre la session avec le serveur web
session_start();

// je me connecte à la base de données pour exécuter mes requêtes SQL
$conn = pdo_connect_bdd();

if(isset($_GET["action"]) && $_GET["action"] == "delete-animal"){
    // su^ppression de l'animal dont l'id est précisé dans la requête

    // supprimer le lien dans la table de liaison user_animal avant
    $stmt = $conn->prepare("DELETE FROM user_animal WHERE animal_id = :animal_id");
    $stmt->bindParam(':animal_id', $_GET["animal_id"]);
    $stmt->execute();

    // puis supprimer l'animal dans la table animal
    $stmt = $conn->prepare("DELETE FROM animal WHERE animal.id = :animal_id");
    $stmt->bindParam(':animal_id', $_GET["animal_id"]);
    $stmt->execute();

    if ($_SESSION["membre"] == 0) {
        header('Location: ' . 'my-account.php');
    } else {
        header('Location: ' . 'admin.php');
    }
}else if(isset($_GET["action"]) && $_GET["action"] == "delete-user"){
    // suppression du user dont l'id est précisé dans la requête
    // On récupère tous les animaux du user
    $stmt = $conn->prepare("SELECT animal_id FROM user_animal where user_animal.user_id=:user_id");
    $stmt->bindParam(':user_id', $_GET["user_id"]);
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $id_animals = $stmt->fetchAll();

    // suppression des références à l'utilisateur dans la table de liaison user_animal
    $stmt = $conn->prepare("DELETE FROM user_animal WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_GET["user_id"]);
    $stmt->execute();

    // suppression de tous les animaux de la table animal qui n'ont aucune référence dans la table user_animal
    foreach ($id_animals as $id_animal) {
        $stmt = $conn->prepare("DELETE FROM animal where id =:animal.id");
        $stmt->bindParam(':animal.id', $id_animal["animal_id"]);
        $stmt->execute();
    }

    // suppression de l'utilisateur
    $stmt = $conn->prepare("DELETE FROM user WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_GET["user_id"]);
    $stmt->execute();
}