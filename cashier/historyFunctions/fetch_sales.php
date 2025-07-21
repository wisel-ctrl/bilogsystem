<?php
require_once '../../db_connect.php';

try {
    // Get date range parameters if they exist
    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;

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
        WHERE s.sales_type = 'walk-in'
    ";

    // Add date range conditions if parameters are provided
    if ($startDate && $endDate) {
        $sql .= " AND DATE(s.created_at) BETWEEN :start_date AND :end_date";
    }

    $sql .= " GROUP BY s.sales_id, s.total_price, s.created_at, s.discount_type
              ORDER BY s.created_at DESC";

    // Prepare the query
    $stmt = $conn->prepare($sql);

    // Bind parameters if date range is provided
    if ($startDate && $endDate) {
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
    }

    // Execute the query
    $stmt->execute();
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON
    header('Content-Type: application/json');
    echo json_encode($sales);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}