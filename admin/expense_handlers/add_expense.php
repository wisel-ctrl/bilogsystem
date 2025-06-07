<?php
header('Content-Type: application/json');

require_once "../../db_connect.php";

try {
    
    // Set timezone to Asia/Manila
    $conn->exec("SET time_zone = '+08:00'");
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Insert new expense
    $stmt = $conn->prepare("INSERT INTO expense_tb (expense_name, expense_category, amount, notes) VALUES (:name, :category, :amount, :notes)");
    $stmt->bindParam(':name', $data['expense_name']);
    $stmt->bindParam(':category', $data['expense_category']);
    $stmt->bindParam(':amount', $data['amount']);
    $stmt->bindParam(':notes', $data['notes']);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add expense']);
    }
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
$conn = null;
?>