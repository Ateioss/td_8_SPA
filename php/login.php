<?php
include_once("class/User.php");
include_once ("bdd/bdd-pdo.php");

// je démarre la session avec le serveur web
session_start();

// je me connecte à la base de données pour exécuter mes requêtes SQL
$conn = pdo_connect_bdd();

//je vérifie que le couple login/mdp de l'user existe
$message="";

if (isset($_POST) && isset($_POST["password"])){
    $user= new User("", $_POST["password"],"", $_POST["email"]);
    $stmt = $conn->prepare("SELECT id, email, lastname, firstname, password, member FROM USER WHERE password=:password and email= :email");
    $stmt->bindParam(':password', $user->password);
    $stmt->bindParam(':email', $user->email);
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $user_one = $stmt->fetch();
    if ($user_one != null){
        // Enregistre le user dans la session
        $_SESSION["user"] = $user_one["id"];
        $_SESSION["membre"] = $user_one['member'];

        if ($user_one['member']==0){
            header('Location: '.'my-account.php');
        }
        else{
            header('Location: '.'admin.php');
        }
    }else{
        $message="user doesn't exist";
    }
}

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
        <a href="login.php">Se connecter</a>
        <span>|</span>
        <a href="registry.php">S'inscrire</a>
    </nav>
</header>
<main class="container" style="height:70vh;">
    <div class="row">
        <div class="col-md-6">
            <div id="form-login" class="card">
                <form action="login.php" class="box" method="post">
                    <h1>Login</h1>
                    <?php if ($message != ""){?>
                        <p class="text-muted" style="padding: 10px; background-color: darkseagreen; border-radius: 5px; border:  2px solid crimson;"> The account doesn't exist !!! </p>
                    <?php }?>
                    <p class="text-muted"> Please enter your login and password!</p>
                    <input type="email" name="email" placeholder="Email">
                    <input type="password" name="password" placeholder="Password">
                    <input type="submit" name="" value="Login">
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