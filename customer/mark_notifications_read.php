<?php
require_once '../db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("
        UPDATE notifications_tb 
        SET is_read = TRUE 
        WHERE user_id = :user_id AND is_read = FALSE
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}