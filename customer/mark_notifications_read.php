<?php
require_once 'customer_auth.php';
require_once '../db_connect.php';

header('Content-Type: application/json');

try {
    // Get the current user ID from session
    $user_id = $_SESSION['user_id'];
    
    // Update all unread notifications for this user
    $stmt = $conn->prepare("UPDATE notifications_tb SET is_read = 1 WHERE user_id = :user_id AND is_read = 0");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Get the number of affected rows
    $affectedRows = $stmt->rowCount();
    
    echo json_encode([
        'success' => true,
        'affected_rows' => $affectedRows
    ]);
} catch (Exception $e) {
    error_log("Error marking notifications as read: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}