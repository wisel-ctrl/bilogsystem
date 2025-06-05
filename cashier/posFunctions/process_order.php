<?php
require_once '../../db_connect.php';

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!$data || !isset($data['total_price'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

try {
    $conn->beginTransaction();
    
    // Insert into sales_tb
    $stmt = $conn->prepare("
        INSERT INTO sales_tb (total_price, discount_type, discount_price, sales_type) 
        VALUES (:total_price, :discount_type, :discount_price, 'walk-in')
    ");
    
    $stmt->execute([
        ':total_price' => $data['total_price'],
        ':discount_type' => $data['discount_type'] ?? 'none',
        ':discount_price' => $data['discount_price'] ?? 0
    ]);
    
    $sales_id = $conn->lastInsertId();
    
    // Insert into order_tb for each item
    $stmt = $conn->prepare("
        INSERT INTO order_tb (sales_id, dish_id, price, quantity) 
        VALUES (:sales_id, :dish_id, :price, :quantity)
    ");
    
    foreach ($data['items'] as $item) {
        $stmt->execute([
            ':sales_id' => $sales_id,
            ':dish_id' => $item['dish_id'],
            ':price' => $item['price'],
            ':quantity' => $item['quantity']
        ]);
    }
    
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'sales_id' => $sales_id
    ]);
    
} catch (PDOException $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>