<?php
header('Content-Type: application/json');

require_once "../../db_connect.php";

try {
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // "Delete" expense by setting status to hidden
    $stmt = $conn->prepare("UPDATE expense_tb SET status = 'hidden' WHERE expense_id = :id");
    $stmt->bindParam(':id', $data['expense_id']);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete expense']);
    }
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
$conn = null;
?>