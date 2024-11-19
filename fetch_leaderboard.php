<?php
global $db;
require 'db_connection.php';

$result = $db->query("SELECT name, money FROM user ORDER BY money DESC LIMIT 10");
$leaderboard = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $leaderboard[] = [
        'name' => htmlspecialchars($row['name']),
        'money' => htmlspecialchars($row['money'])
    ];
}

echo json_encode($leaderboard);
?>