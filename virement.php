<?php
global $db;
session_start();
require 'db_connection.php';

$message = '';
$user_money = 0;
$user_name = '';

if (isset($_SESSION['user_id'])) {
    $stmt = $db->prepare("SELECT name, money FROM user WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_name = $user['name'];
    $user_money = $user['money'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipient = htmlspecialchars($_POST['recipient']);
    $amount = floatval($_POST['amount']);
    $sender_id = $_SESSION['user_id'];

    if ($amount > 0) {
        // Check if recipient exists
        $stmt = $db->prepare("SELECT * FROM user WHERE name = ?");
        $stmt->execute([$recipient]);
        $recipient_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($recipient_user) {
            // Check if sender has enough money
            $stmt = $db->prepare("SELECT money FROM user WHERE id = ?");
            $stmt->execute([$sender_id]);
            $sender_money = $stmt->fetchColumn();

            if ($sender_money >= $amount) {
                // Deduct amount from sender
                $stmt = $db->prepare("UPDATE user SET money = money - ? WHERE id = ?");
                $stmt->execute([$amount, $sender_id]);

                // Add amount to recipient
                $stmt = $db->prepare("UPDATE user SET money = money + ? WHERE id = ?");
                $stmt->execute([$amount, $recipient_user['id']]);

                $message = "Virement réussi.";
                $user_money -= $amount; // Update the displayed balance
            } else {
                $message = "Fonds insuffisants.";
            }
        } else {
            $message = "Utilisateur destinataire introuvable.";
        }
    } else {
        $message = "Montant invalide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virement</title>
    <link rel="stylesheet" href="styles/style.css">
    <script>
        function searchUser(query) {
            if (query.length == 0) {
                document.getElementById("userSuggestions").innerHTML = "";
                return;
            }
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "search_user.php?q=" + query, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("userSuggestions").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
<div class="form-container">
    <h2 class="title">Virement</h2>
    <p class="dynamic-font-size"><?php echo number_format($user_money, 2); ?> €</p>
    <form class="form" action="virement.php" method="post">
        <div class="input-group">
            <label for="recipient">Nom du destinataire</label>
            <input type="text" id="recipient" name="recipient" onkeyup="searchUser(this.value)" required>
            <div id="userSuggestions"></div>
        </div>
        <div class="input-group">
            <label for="amount">Montant</label>
            <input type="number" step="0.01" id="amount" name="amount" required>
        </div>
        <br><br>
        <button type="submit" class="sign">Envoyer</button>
        <p>Revenir a <a href="index.php">l'acceuil</a></p>
    </form>
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
                window.location.href = 'virement.php';
            }

            window.onclick = function(event) {
                if (event.target == popup) {
                    popup.style.display = 'none';
                    window.location.href = 'virement.php';
                }
            }
        </script>
    <?php endif; ?>
</div>
</body>
</html>