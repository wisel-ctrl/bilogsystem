<?php
require_once '../admin_auth.php';
require_once '../../db_connect.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['user_id']) || $data['user_id'] != $_SESSION['user_id']) {
        throw new Exception("Invalid user ID");
    }
    
    $stmt = $conn->prepare("UPDATE notifications_tb SET is_read = 1 WHERE user_id = :user_id AND is_read = 0");
    $stmt->execute([':user_id' => $data['user_id']]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log("Error marking notifications as read: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}