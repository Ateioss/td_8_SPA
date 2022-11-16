<?php
include_once("class/User.php");
include_once("class/Animal.php");
include_once ("bdd/bdd-pdo.php");

// je démarre la session avec le serveur web
session_start();

// je me connecte à la base de données pour exécuter mes requêtes SQL
$conn = pdo_connect_bdd();

//lecture des animaux de l'utilisateurs sur lequel tu as cliqué
$user_selected_id = -1;
$animals = array();
if(isset($_GET) && isset($_GET["user_id"])){
    $user_selected_id = $_GET["user_id"];

    $stmt = $conn->prepare("SELECT id, name, type FROM animal inner join user_animal on user_animal.animal_id = animal.id where user_animal.user_id=:user_id");
    $stmt->bindParam(':user_id', $user_selected_id);

    // J'exécute ma requête SQL
    $stmt->execute();
    $count_animals = 0;
    while($data = $stmt->fetch()){
        $animal = new Animal($data['name'], $data['type'], $data['id']);
        $animals [$count_animals] = $animal;
        $count_animals++;
    }
}

// Lecture des animaux en base de données
$stmt = $conn->prepare("SELECT id, firstname, lastname, email, member, password FROM user");

// J'exécute ma requête SQL
$stmt->execute();

// je recupère le résultat de la requête exécutée que je mets dans un tableau (je construis une liste d'animaux)
$users = array();
$count_users = 0;
$user_selected = null;
while($data = $stmt->fetch()){
    $user= new User($data["lastname"],$data["password"], $data ["firstname"], $data["email"], $data["id"]);
    $users [$count_users] = $user;
    $count_users++;

    if($user->id == $user_selected_id){
        $user_selected = $user;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link href="../css/styles.css" rel="stylesheet">
    <style>
        .container-admin{
            height:80vh;
            padding:10px;
            overflow: auto;
        }
        .list-users{
            display:flex;
            flex-direction:column;
            gap:15px;
            width:30%;
            border-right:1px solid white;
            padding-left:10px;
            margin-top: 20px;
        }

        .item-user{
            text-decoration:none;
            color:white;
            padding:20px;
            padding-top:10px;
            padding-bottom: 10px;
            border-radius: 20px;
            width:50%;
            display: block;
            background : linear-gradient(to right, #1565c0, #b92b27);
        }

        .item-user:hover{
            background : darkseagreen;
        }

        .sub-title{
            border-bottom:1px solid white;
            color:white;
        }
    </style>
</head>
<body>
<header>
    <div class="header-logo"></div>
    <nav class="header-menu">
        <a href="admin.php">Admin</a>
        <span>|</span>
        <a href="login.php">Se connecter</a>
        <span>|</span>
        <a href="registry.php">S'inscrire</a>
    </nav>
</header>
<main class="container container-admin">
    <h3 class="my-account-main-title">Users and theirs animals</h3>
    <div style="display:flex;">
        <div class="list-users">
            <?php foreach ($users as $user){?>
            <div>
                <a class="item-user" href="admin.php?action=select-users&user_id=<?= $user->id ?>"> <?= $user->firstname . " " . $user->lastname ?> </a>
            </div>
            <?php } ?>
        </div>
        <div style="width:70%;padding-left:10px;padding-right:10px;">
            <div>
                <?php if($user_selected != null) {?>
                <p class="sub-title"><?= $user_selected->firstname . " " . $user_selected->lastname ?></p>
                <?php } ?>
            </div>
            <?php if($user_selected != null) {?>
            <div class="animal" style="width:50%;margin:auto;">
                <p>Nom : <?= $user_selected->firstname ?></p>
                <p>Prénom : <?= $user_selected->lastname ?></p>
                <p>Email : <?= $user_selected->email ?></p>
                <a href="action-delete.php?action=delete-user&user_id=<?= $user_selected->id ?>">Delete</a>
            </div>
            <?php } ?>
            <div>
                <p class="sub-title">Theses animals</p>
            </div>
            <div class="animals" style="width:100%;">
                <?php foreach ($animals as $animal){?>
                <div class="animal">
                    <p><?= $animal->name ?>></p>
                    <p><?= $animal->type ?></p>
                    <a href="action-delete.php?action=delete-animal&animal_id=<?= $animal->id ?>">Delete</a>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</main>
<footer>
</footer>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>