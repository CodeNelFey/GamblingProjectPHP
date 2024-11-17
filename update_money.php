<?php
global $db;
session_start();
require 'db_connection.php';

if (isset($_SESSION['user_id']) && isset($_POST['money'])) {
    $userId = $_SESSION['user_id'];
    $newMoney = $_POST['money'];

    $stmt = $db->prepare("UPDATE user SET money = :money WHERE id = :id");
    $stmt->bindParam(':money', $newMoney);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();

    $_SESSION['user_money'] = $newMoney;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not logged in or money not set']);
}
?>