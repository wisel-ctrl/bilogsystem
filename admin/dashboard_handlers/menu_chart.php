<?php
header('Content-Type: application/json');

// Database configuration
require_once '../../db_connect.php'; // Assuming your PDO connection is in config.php

try {
   
    

    // Query to get top-selling dishes
    $query = "
        SELECT
            d.dish_id,
            d.dish_name,
            SUM(o.quantity) AS total_quantity
        FROM
            order_tb AS o
        JOIN
            dishes_tb AS d ON d.dish_id = o.dish_id
        GROUP BY
            d.dish_id, d.dish_name
        ORDER BY
            total_quantity DESC
        LIMIT 10
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process results for chart
    $labels = [];
    $data = [];
    
    foreach ($results as $row) {
        $labels[] = $row['dish_name'];
        $data[] = (int)$row['total_quantity'];
    }

    // Extended color palette that matches your existing theme
    $backgroundColors = [
        '#8B4513', // Original brown (SaddleBrown)
        '#A0522D', // Original (Sienna)
        '#5D2F0F', // Original dark brown
        '#E8E0D5', // Original light beige
        '#D2B48C', // Original tan
        '#CD853F', // Original (Peru)
        '#B8860B', // New: DarkGoldenrod
        '#DAA520', // New: Goldenrod
        '#F4A460', // New: SandyBrown
        '#DEB887', // New: BurlyWood
        '#BC8F8F', // New: RosyBrown
        '#A0522D', // Repeat colors if needed
    ];

    // Prepare response
    $response = [
        'labels' => $labels,
        'datasets' => [
            [
                'data' => $data,
                'backgroundColor' => array_slice($backgroundColors, 0, count($labels)),
                'borderWidth' => 2,
                'borderColor' => '#fff'
            ]
        ]
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>