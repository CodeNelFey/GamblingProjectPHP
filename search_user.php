<?php
global $db;
require 'db_connection.php';

if (isset($_GET['q'])) {
    $query = htmlspecialchars($_GET['q']);
    $stmt = $db->prepare("SELECT name FROM user WHERE name LIKE ? LIMIT 10");
    $stmt->execute([$query . '%']);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        echo "<p onclick=\"document.getElementById('recipient').value = '" . htmlspecialchars($user['name']) . "'\">" . htmlspecialchars($user['name']) . "</p>";
    }
}
?>