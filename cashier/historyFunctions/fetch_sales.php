<?php
require_once 'cashier_auth.php';
require_once '../../db_connect.php';

try {
    // SQL query defined first
    $sql = "
        SELECT 
            s.sales_id,
            s.total_price,
            s.created_at,
            s.discount_type,
            COALESCE(
                CAST(SUM(o.quantity) AS CHAR),
                'past data'
            ) AS items
        FROM sales_tb s
        LEFT JOIN order_tb o ON s.sales_id = o.sales_id
        GROUP BY s.sales_id, s.total_price, s.created_at, s.discount_type
        ORDER BY s.created_at DESC
    ";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON
    header('Content-Type: application/json');
    echo json_encode($sales);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
