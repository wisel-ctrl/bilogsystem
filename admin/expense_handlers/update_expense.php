<?php
header('Content-Type: application/json');

require_once "../../db_connect.php";

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Update expense
    $stmt = $conn->prepare("UPDATE expense_tb SET expense_name = :name, expense_category = :category, amount = :amount, notes = :notes WHERE expense_id = :id");
    $stmt->bindParam(':name', $data['expense_name']);
    $stmt->bindParam(':category', $data['expense_category']);
    $stmt->bindParam(':amount', $data['amount']);
    $stmt->bindParam(':notes', $data['notes']);
    $stmt->bindParam(':id', $data['expense_id']);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update expense']);
    }
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
$conn = null;
?>