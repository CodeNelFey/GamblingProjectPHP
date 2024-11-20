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
global $db;
require 'db_connection.php';

session_start();
if (isset($_SESSION['user_name']) && isset($_SESSION['user_money'])) {
    $username = htmlspecialchars($_SESSION['user_name']);
    $money = htmlspecialchars($_SESSION['user_money']);
    echo "<div class='user-info'>
            <p class='name'><img src='./imgs/User_fill.svg' alt=''>$username</p>
            <p class='money' id='money'><img src='./imgs/Dimond_alt.svg' alt=''>$money \$</p>
            <a href='virement.php' class='button virement'>Virement</a>
            <div class='infouser'>
                <form action='logout.php' method='post'>
                    <button type='submit' class='logout-button'><p>Déconnexion</p>
                    <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                        <path d='M2 12L1.21913 11.3753L0.719375 12L1.21913 12.6247L2 12ZM11 13C11.5523 13 12 12.5523 12 12C12 11.4477 11.5523 11 11 11V13ZM5.21913 6.3753L1.21913 11.3753L2.78087 12.6247L6.78087 7.6247L5.21913 6.3753ZM1.21913 12.6247L5.21913 17.6247L6.78087 16.3753L2.78087 11.3753L1.21913 12.6247ZM2 13H11V11H2V13Z' fill='#fff'/>
                        <path d='M10 8.13193V7.38851C10 5.77017 10 4.961 10.474 4.4015C10.9479 3.84201 11.7461 3.70899 13.3424 3.44293L15.0136 3.1644C18.2567 2.62388 19.8782 2.35363 20.9391 3.25232C22 4.15102 22 5.79493 22 9.08276V14.9172C22 18.2051 22 19.849 20.9391 20.7477C19.8782 21.6464 18.2567 21.3761 15.0136 20.8356L13.3424 20.5571C11.7461 20.291 10.9479 20.158 10.474 19.5985C10 19.039 10 18.2298 10 16.6115V16.066' stroke='#fff' stroke-width='2'/>
                    </svg>
                    </button>
                </form>
            </div>
          </div>";
} else {
    echo "<p class='notif-msg-bad'>Informations utilisateur non disponibles. <br> Vous n'êtes pas connecté, merci de vous reconnecter. <a href='login.php'>Se connecter</a></p>";
}
?>

<div class="container">
    <div class="game">
        <button id="gambleButton">100</button>
        <div id="priceFilter" class="filter-switch">
            <input checked="" id="bet100" name="priceOptions" type="radio" />
            <label class="option" for="bet100">Bet 100</label>
            <input id="bet1000" name="priceOptions" type="radio" />
            <label class="option" for="bet1000">Bet 1000</label>
            <input id="x2" name="priceOptions" type="radio" />
            <label class="option" for="x2">X2</label>
            <input id="allIn" name="priceOptions" type="radio" />
            <label class="option" for="allIn">All In</label>
            <span class="background"></span>
        </div>
    </div>

    <div class="leaderboard">
        <h2>Top 10 Players</h2>
        <ul id="leaderboard">
            <?php
            $result = $db->query("SELECT name, money FROM user ORDER BY money DESC LIMIT 10");

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<li><p>" . htmlspecialchars($row['name']) . ": " . htmlspecialchars($row['money']) . " \$</p></li>";
            }
            ?>
        </ul>
    </div>
</div>

<script>
    function updateLeaderboard() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_leaderboard.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var leaderboard = JSON.parse(xhr.responseText);
                var leaderboardElement = document.getElementById('leaderboard');
                leaderboardElement.innerHTML = '';
                leaderboard.forEach(function(player, index) {
                    var li = document.createElement('li');
                    li.innerHTML = '<p><span class="rank">' + (index + 1) + '.</span> <span class="name">' + player.name + '</span> <span class="money">' + player.money + ' $</span></p>';
                    if (index === 0) {
                        li.classList.add('gold');
                    } else if (index === 1) {
                        li.classList.add('silver');
                    } else if (index === 2) {
                        li.classList.add('bronze');
                    }
                    leaderboardElement.appendChild(li);
                });
            }
        };
        xhr.send();
    }

    setInterval(updateLeaderboard, 1000);

    document.getElementById('x2').addEventListener('change', function() {
        if (this.checked) {
            var backgroundSpan = document.querySelector('.background');
            var gambleImage = document.getElementById('gambleButton');
            backgroundSpan.classList.remove('goldbg');
            gambleImage.style.backgroundImage = 'url("../imgs/gambling_piece.png")'
            gambleImage.textContent = '1/2'
            gambleImage.style.color = '#212121'
        }
    });

    document.getElementById('bet100').addEventListener('change', function() {
        if (this.checked) {
            var backgroundSpan = document.querySelector('.background');
            var gambleImage = document.getElementById('gambleButton');
            backgroundSpan.classList.remove('goldbg');
            gambleImage.style.backgroundImage = 'url("../imgs/gambling_piece.png")'
            gambleImage.textContent = '100'
            gambleImage.style.color = '#212121'
        }
    });

    document.getElementById('bet1000').addEventListener('change', function() {
        if (this.checked) {
            var backgroundSpan = document.querySelector('.background');
            var gambleImage = document.getElementById('gambleButton');
            backgroundSpan.classList.remove('goldbg');
            gambleImage.style.backgroundImage = 'url("../imgs/gambling_piece.png")'
            gambleImage.textContent = '1000'
            gambleImage.style.color = '#212121'
        }
    });

    document.getElementById('allIn').addEventListener('change', function() {
        if (this.checked) {
            var backgroundSpan = document.querySelector('.background');
            var gambleImage = document.getElementById('gambleButton');
            backgroundSpan.classList.add('goldbg');
            gambleImage.style.backgroundImage = 'url("../imgs/gambling_piece_gold.png")'
            gambleImage.textContent = 'All IN'
            gambleImage.style.color = '#fff'
        }
    });

    document.getElementById('gambleButton').addEventListener('click', function() {
        var moneyElement = document.getElementById('money');
        var money = parseFloat(moneyElement.textContent);
        var selectedBet = document.querySelector('input[name="priceOptions"]:checked').id;

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

        moneyElement.innerHTML = '<img src="./imgs/Dimond_alt.svg" alt=""> ' + money.toFixed(2) + ' $';

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

    document.getElementById('add1000Button').addEventListener('click', function() {
        var moneyElement = document.getElementById('money');
        var money = parseFloat(moneyElement.textContent);
        money += 1000;
        moneyElement.innerHTML = '<img src="./imgs/Dimond_alt.svg" alt=""> ' + money.toFixed(2) + ' $';

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
<footer>

</footer>
</html>