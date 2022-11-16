<?php
include_once("class/animal.php");
include_once ("bdd/bdd-pdo.php");

// je démarre la session avec le serveur web
session_start();

// je me connecte à la base de données pour exécuter mes requêtes SQL
$conn = pdo_connect_bdd();

if (isset($_POST) && isset($_POST["name"])){
    $animal= new Animal($_POST["name"],$_POST["type"]);
    $stmt = $conn->prepare("INSERT INTO animal (name, type)VALUES(:name,:type)");
    $stmt->bindParam(':name', $animal->name);
    $stmt->bindParam(':type', $animal->type);
    $stmt->execute();
    $animal_id = $conn->lastInsertId();

    // insertion dans la table de liaison user_animal
    $stmt = $conn->prepare("INSERT INTO user_animal (animal_id, user_id)VALUES(:animal_id,:user_id)");
    $stmt->bindParam(':animal_id', $animal_id);
    $stmt->bindParam(':user_id', $_SESSION["user"]);
    $stmt->execute();

    header('Location: '.'my-account.php');
}

$conn = null;
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
    <h3 class="my-account-main-title">Add animal</h3>
    <div class="box">
        <form  action="my-account-add.php" method="post">
            <input type="text" name="name" placeholder="Name" required>
            <select name="type" required>
                <option value="Dog">Dog</option>
                <option value="Cat">Cat</option>
                <option value="Parrot">Parrot</option>
                <option value="Raptor">Raptor</option>
            </select>
            <input type="submit" name="submit" value="Save">
        </form>
    </div>
</main>
<footer>
</footer>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>