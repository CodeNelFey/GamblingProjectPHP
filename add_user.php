<?php
global $db;
require 'db_connection.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO user (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);
        header('Location: login.php');
        exit();
    } else {
        $message = "Les mots de passes ne sont pas identiques.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<div class="content-page">
    <div class="form-container">
        <h2 class="title">Crée un compte</h2>
        <form class="form" action="add_user.php" method="post">
            <div class="input-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <div class="password">
                    <input type="password" id="password" name="password" required>
                    <img id="view_fill" src="./imgs/View_fill.svg" alt="View" style="display: block;">
                    <img id="view_hide_fill" src="./imgs/View_hide_fill.svg" alt="Hide" style="display: none;">
                </div>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirme ton mot de passe</label>
                <div class="password">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <img id="confirm_view_fill" src="./imgs/View_fill.svg" alt="View" style="display: block;">
                    <img id="confirm_view_hide_fill" src="./imgs/View_hide_fill.svg" alt="Hide" style="display: none;">
                </div>
            </div>
            <br><br>
            <button type="submit" class="sign">Crée mon compte</button>
            <p class="signup">J'ai deja un compte
                <a rel="noopener noreferrer" href="login.php" class="">Me connecter</a>
            </p>
        </form>
    </div>
</div>

<?php if ($message): ?>
    <div id="errorPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <p><?php echo $message; ?></p>
        </div>
    </div>
    <script>
        var popup = document.getElementById('errorPopup');
        var span = document.getElementsByClassName('close')[0];

        popup.style.display = 'block';

        span.onclick = function() {
            popup.style.display = 'none';
            window.location.href = 'add_user.php';
        }

        window.onclick = function(event) {
            if (event.target == popup) {
                popup.style.display = 'none';
                window.location.href = 'add_user.php';
            }
        }
    </script>
<?php endif; ?>

<script>
    document.getElementById('view_fill').addEventListener('click', function() {
        var passwordInput = document.getElementById('password');
        var viewFill = document.getElementById('view_fill');
        var viewHideFill = document.getElementById('view_hide_fill');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            viewFill.style.display = 'none';
            viewHideFill.style.display = 'block';
        }
    });

    document.getElementById('view_hide_fill').addEventListener('click', function() {
        var passwordInput = document.getElementById('password');
        var viewFill = document.getElementById('view_fill');
        var viewHideFill = document.getElementById('view_hide_fill');
        if (passwordInput.type === 'text') {
            passwordInput.type = 'password';
            viewFill.style.display = 'block';
            viewHideFill.style.display = 'none';
        }
    });

    document.getElementById('confirm_view_fill').addEventListener('click', function() {
        var confirmPasswordInput = document.getElementById('confirm_password');
        var confirmViewFill = document.getElementById('confirm_view_fill');
        var confirmViewHideFill = document.getElementById('confirm_view_hide_fill');
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            confirmViewFill.style.display = 'none';
            confirmViewHideFill.style.display = 'block';
        }
    });

    document.getElementById('confirm_view_hide_fill').addEventListener('click', function() {
        var confirmPasswordInput = document.getElementById('confirm_password');
        var confirmViewFill = document.getElementById('confirm_view_fill');
        var confirmViewHideFill = document.getElementById('confirm_view_hide_fill');
        if (confirmPasswordInput.type === 'text') {
            confirmPasswordInput.type = 'password';
            confirmViewFill.style.display = 'block';
            confirmViewHideFill.style.display = 'none';
        }
    });
</script>
</body>
</html>