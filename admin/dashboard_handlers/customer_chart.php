<?php
header('Content-Type: application/json');
require_once("../../db_connect.php");

try {
    
    $query = "
    SELECT 
        category,
        ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM ratings), 2) AS percentage
    FROM (
        SELECT 
            CASE 
                WHEN avg_rating >= 4.5 THEN 'Excellent'
                WHEN avg_rating >= 3.5 THEN 'Good'
                WHEN avg_rating >= 2.5 THEN 'Average'
                ELSE 'Poor'
            END AS category
        FROM (
            SELECT 
                (ambiance_rating + food_rating + reservation_rating + service_rating) / 4.0 AS avg_rating
            FROM ratings
        ) AS avg_ratings
    ) AS categorized_ratings
    GROUP BY category;
    ";
    
    $stmt = $conn->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Initialize all categories with 0% in case some are missing
    $categories = [
        ['category' => 'Excellent', 'percentage' => 0],
        ['category' => 'Good', 'percentage' => 0],
        ['category' => 'Average', 'percentage' => 0],
        ['category' => 'Poor', 'percentage' => 0]
    ];
    
    // Update with actual values
    foreach ($results as $result) {
        foreach ($categories as &$category) {
            if ($category['category'] === $result['category']) {
                $category['percentage'] = $result['percentage'];
                break;
            }
        }
    }
    
    echo json_encode($categories);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}