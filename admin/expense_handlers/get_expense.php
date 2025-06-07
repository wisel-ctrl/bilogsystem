<?php
header('Content-Type: application/json');
require_once "../../db_connect.php";

if(!isset($_GET['id'])) {
    echo json_encode(['error' => 'No expense ID provided']);
    exit;
}

try {
    
    
    // Get single expense
    $stmt = $conn->prepare("SELECT * FROM expense_tb WHERE expense_id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    
    $expense = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($expense) {
        echo json_encode($expense);
    } else {
        echo json_encode(['error' => 'Expense not found']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
$conn = null;
?>