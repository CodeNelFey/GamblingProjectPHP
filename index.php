<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gambling</title>
    <link rel="stylesheet" href="./styles/styleGambling.css">
</head>
<body>

<?php
session_start();
if (isset($_SESSION['user_name']) && isset($_SESSION['user_money'])) {
    $username = htmlspecialchars($_SESSION['user_name']);
    $money = htmlspecialchars($_SESSION['user_money']);
    echo "<div class='user-info'>
            <p class='name'>$username</p>
            <p class='money' id='money'>$money \$</p>
          </div>";
} else {
    echo "<p class='notif-msg-bad'>Informations utilisateur non disponibles. <br> Vous n'êtes pas connecté, merci de vous reconnecter. <a href='login.php'>Se connecter</a></p>";
}
?>

<div class="game">
    <button id="gambleButton">Gamble</button>
    <div class="select-menu">
        <button id="bet100Button">100</button>
        <button id="bet1000Button">1000</button>
        <button id="x2Button">1/2</button>
        <button id="allInButton">All In</button>
    </div>
</div>

<script>
    let selectedBet = null;

    function selectBet(bet) {
        selectedBet = bet;
        document.querySelectorAll('.game button').forEach(button => {
            button.classList.remove('selected');
        });
        document.getElementById(bet + 'Button').classList.add('selected');
    }

    document.getElementById('bet100Button').addEventListener('click', () => selectBet('bet100'));
    document.getElementById('bet1000Button').addEventListener('click', () => selectBet('bet1000'));
    document.getElementById('x2Button').addEventListener('click', () => selectBet('x2'));
    document.getElementById('allInButton').addEventListener('click', () => selectBet('allIn'));

    document.getElementById('gambleButton').addEventListener('click', function() {
        if (!selectedBet) {
            alert('Please select a bet mode.');
            return;
        }

        var moneyElement = document.getElementById('money');
        var money = parseFloat(moneyElement.textContent);

        switch (selectedBet) {
            case 'bet100':
                if (money >= 100) {
                    money -= 100;
                    if (Math.random() < 0.5) {
                        money += 200;
                    }
                } else {
                    alert('Not enough money to bet 100.');
                }
                break;
            case 'bet1000':
                if (money >= 1000) {
                    money -= 1000;
                    if (Math.random() < 0.5) {
                        money += 2000;
                    }
                } else {
                    alert('Not enough money to bet 1000.');
                }
                break;
            case 'x2':
                if (Math.random() < 0.5) {
                    money /= 2;
                } else {
                    money *= 2;
                }
                break;
            case 'allIn':
                var bet = money;
                if (Math.random() < 0.5) {
                    money = 0;
                } else {
                    money *= 2;
                }
                break;
        }

        moneyElement.textContent = money.toFixed(2) + ' $';

        // Update the session value via an AJAX request to a PHP script
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_money.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (!response.success) {
                    alert('Failed to update money in the database: ' + response.message);
                }
            }
        };
        xhr.send('money=' + money.toFixed(2));
    });
</script>

</body>
</html>