<?php
require_once '../../db_connect.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$field = $input['field'] ?? '';
$value = $input['value'] ?? '';
$currentUserId = $input['current_user_id'] ?? 0;

// Validate field
$allowedFields = ['username', 'contact_number'];
if (!in_array($field, $allowedFields)) {
    echo json_encode(['available' => false, 'message' => 'Invalid field']);
    exit;
}

try {
    // Check if the value exists for another user
    $query = "SELECT id FROM users_tb WHERE $field = :value AND id != :user_id LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':value', $value);
    $stmt->bindParam(':user_id', $currentUserId, PDO::PARAM_INT);
    $stmt->execute();
    
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(['available' => !$exists]);
} catch (PDOException $e) {
    echo json_encode(['available' => false, 'message' => 'Database error']);
}