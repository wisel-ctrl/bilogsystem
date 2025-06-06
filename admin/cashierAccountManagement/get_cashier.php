<?php
require_once '../../db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $cashierId = $_GET['id'];
    
    try {
        $stmt = $conn->prepare("SELECT id, first_name, middle_name, last_name, suffix, username, contact_number 
                               FROM users_tb 
                               WHERE id = ? AND usertype = 2 AND status = 1");
        $stmt->execute([$cashierId]);
        $cashier = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cashier) {
            echo json_encode([
                'success' => true,
                'cashier' => $cashier
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Cashier not found'
            ]);
        }
    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error fetching cashier: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>