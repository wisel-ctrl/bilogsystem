<?php
header('Content-Type: application/json');

require_once "../../db_connect.php";

try {
    
    
    // Set timezone to Asia/Manila
    $conn->exec("SET time_zone = '+08:00'");
    
    // Get expenses with status 'show'
    $stmt = $conn->prepare("SELECT * FROM expense_tb WHERE status = 'show' ORDER BY created_at DESC");
    $stmt->execute();
    
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($expenses);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
$conn = null;
?>