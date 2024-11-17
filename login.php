<?php
global $db;
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Connexion</title>
</head>
<body>

<div class="form-container">
    <p class="title">Connexion</p>
    <?php
    if (isset($_SESSION['user_name'])) {
        echo "<p>Bienvenue, " . $_SESSION['user_name'] . " !</p>";
    } else {
        echo '<form class="form" action="login.php" method="post">
                <div class="input-group">
                    <label for="name">Nom :</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="input-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password" required>
                    <div class="forgot">
                        <a rel="noopener noreferrer" href="forgot_password.php">Mot de passe oublié ?</a>
                    </div>
                </div>
                <input type="submit" value="Se connecter" class="sign">
              </form>
              <p class="signup">Je n\'ai pas de compte
                <a rel="noopener noreferrer" href="add_user.php" class="">Créer un compte</a>
              </p>';
    }
    ?>

    <?php
    // Traitement du formulaire de connexion
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require 'db_connection.php';

        $name = $_POST['name'];
        $password = $_POST['password'];

        // Recherche de l'utilisateur dans la base de données
        $stmt = $db->prepare("SELECT * FROM user WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_money'] = $user['money'];
            header("Location: index.php");
            exit();
        } else {
            // Connexion échouée
            echo "<p class='notif-msg-bad'>Nom ou mot de passe incorrect.</p>";
        }
    }
    ?>
</div>

</body>
</html>