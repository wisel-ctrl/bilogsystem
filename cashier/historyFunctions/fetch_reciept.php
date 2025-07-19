<?php
header('Content-Type: application/json');

// Database connection
require_once '../../db_connect.php';

try {
    // Get sales_id from POST data
    $sales_id = $_POST['sales_id'] ?? null;
    
    if (!$sales_id) {
        throw new Exception("Sales ID is required");
    }
    
    // Prepare the query
    $query = "
        SELECT 
            s.sales_id,
            s.total_price,
            s.amount_paid,
            s.amount_change,
            s.discount_type,
            s.discount_price,
            s.created_at,
            d.dish_name,
            o.quantity,
            o.price
        FROM sales_tb s
        JOIN order_tb o ON s.sales_id = o.sales_id
        JOIN dishes_tb d ON o.dish_id = d.dish_id
        WHERE s.sales_id = :sales_id
    ";
    
    // Prepare and execute the statement
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':sales_id', $sales_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($results)) {
        throw new Exception("No receipt found for this sales ID");
    }
    
    // Structure the response data
    $response = [
        'success' => true,
        'data' => [
            'sales_id' => $results[0]['sales_id'],
            'total_price' => (float)$results[0]['total_price'],
            'amount_paid' => (float)$results[0]['amount_paid'],
            'amount_change' => (float)$results[0]['amount_change'],
            'discount_type' => $results[0]['discount_type'],
            'discount_price' => (float)$results[0]['discount_price'],
            'created_at' => $results[0]['created_at'],
            'items' => []
        ]
    ];
    
    // Add items to response
    foreach ($results as $row) {
        $response['data']['items'][] = [
            'dish_name' => $row['dish_name'],
            'quantity' => (int)$row['quantity'],
            'price' => (float)$row['price']
        ];
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}