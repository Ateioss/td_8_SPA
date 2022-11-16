<?php
include_once("class/User.php");
include_once("class/Animal.php");
include_once ("bdd/bdd-pdo.php");

// je démarre la session avec le serveur web
session_start();

// je me connecte à la base de données pour exécuter mes requêtes SQL
$conn = pdo_connect_bdd();

// Lecture des animaux en base de données
// 1/je prépare ma requête SQL
$stmt = $conn->prepare("SELECT id, name, type FROM animal inner join user_animal on user_animal.animal_id = animal.id where user_animal.user_id=:user_id");
$stmt->bindParam(':user_id', $_SESSION["user"]);
// 2/ J'exécute ma requête SQL
$stmt->execute();

// je recupère le résultat de la requête exécutée que je mets dans un tableau (je construis une liste d'animaux)
$animals = array();
$count_animals = 0;
while($data = $stmt->fetch()){
    $animal = new Animal($data['name'], $data['type'], $data['id']);
    $animals [$count_animals] = $animal;
    $count_animals++;
}

?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="header-logo"></div>
    <nav class="header-menu">
        <a href="my-account.php">My account</a>
        <span>|</span>
        <a href="login.php">Se connecter</a>
        <span>|</span>
        <a href="registry.php">S'inscrire</a>
    </nav>
</header>
<main class="container" style="height:80vh;padding:10px;">
    <h3 class="my-account-main-title">My animals</h3>
    <div class="animals">

        <?php foreach ($animals as $animal){?>
        <div class="animal">
            <p><?= $animal->name ?></p>
            <p><?= $animal->type ?></p>
            <a href="action-delete.php?action=delete-animal&animal_id=<?=$animal->id?>">Delete</a>
        </div>
        <?php } ?>

    </div>
    <div style="position:fixed; bottom:150px;right:100px;">
        <a class="cta" href="my-account-add.php">Add animal</a>
    </div>
</main>
<footer>
</footer>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>