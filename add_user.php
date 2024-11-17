<?php
global $db;
require 'db_connection.php';
require 'User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($name) && !empty($password)) {
        $user = new User($db);
        $user->addUser($name, $password, 1000.00);
    } else {
        echo "Veuillez remplir tous les champs correctement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Ajouter un utilisateur</title>
</head>
<body>

<div class="form-container">
    <p class="title">Créer un compte</p>
    <form class="form" method="post" action="add_user.php">
        <div class="input-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" placeholder="" required>
        </div>
        <div class="input-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="" required>
            <div class="forgot">
                <a rel="noopener noreferrer" href="#">Mot de passe oublié ?</a>
            </div>
        </div>
        <input type="submit" value="Crée mon compte" class="sign">
    </form>

    <p class="signup">J'ai deja un compte
        <a rel="noopener noreferrer" href="login.php" class="">Me connecter</a>
    </p>
</div>
</body>
</html>
