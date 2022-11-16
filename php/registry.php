<?php
include_once("class/User.php");
include_once ("bdd/bdd-pdo.php");

// je démarre la session avec le serveur web
session_start();

// je me connecte à la base de données pour exécuter mes requêtes SQL
$conn = pdo_connect_bdd();

$message = "";
$user=null;

// création du user

if (isset($_POST) && isset($_POST["lastname"])){
    $user= new User($_POST["lastname"],$_POST["password"], $_POST ["firstname"], $_POST["email"]);

    // vérification exist ds base de donnée
    $stmt = $conn->prepare("SELECT id, email, lastname, firstname, password, member FROM USER WHERE email= :email");
    $stmt->bindParam(':email', $user->email);
    $stmt->execute();
    $user_one = $stmt->fetch();

    if ($user_one != null){
        //the user exist
        $message= 'user exist';
    }
    else{
        $member=0;
        //the user does not exist
        $stmt = $conn->prepare("INSERT INTO user (email, lastname, firstname, password, member)VALUES(:email,:lastname,:firstname,:password,:member)");
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':lastname', $user->lastname);
        $stmt->bindParam(':firstname', $user->firstname);
        $stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':member', $member);
        $stmt->execute();
        $user_id = $conn->lastInsertId();

        header('Location: '.'../index.php');
    }
}

//je ferme ma connection à la base de données
$conn = null;

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="header-logo"></div>
    <nav class="header-menu">
        <a href="admin.php">Admin</a>
        <span>|</span>
        <a href="my-account.php">My account</a>
        <span>|</span>
        <a href="login.php">Se connecter</a>
        <span>|</span>
        <a href="registry.php">S'inscrire</a>
    </nav>
</header>
<main class="container" style="height: 70vh;">
    <div class="row">
        <div class="col-md-6">
            <div id="form-registry" class="card">
                <form action="registry.php"  method="post" class="box">
                    <h1>Registry</h1>
                    <?php if ($message != ""){?>
                        <p class="text-muted" style="padding: 10px; background-color: darkseagreen; border-radius: 5px; border:  2px solid crimson;"> The user exist !!! </p>
                    <?php }?>
                    <p class="text-muted"> Please enter your information to registry !</p>
                    <input type="text" name="firstname" placeholder="Firstname" required value="<?php if ($user != null){echo $user->firstname;}?>">
                    <input type="text" name="lastname" placeholder="Lastname" required value="<?php if ($user != null){echo $user->lastname;}?>">
                    <input type="email" name="email" placeholder="email" required>
                    <input type="password" name="password" placeholder="Password" required value="<?php if ($user != null){echo $user->password;}?>">
                    <input type="submit" name="submit" value="Save">
                </form>
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