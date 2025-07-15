<?php
header('Content-Type: application/json');

require_once("../../db_connect.php");

try {
    // Create PDO connection
    

    // SQL query to get seasonal revenue
    $query = "
        SELECT
            CASE
                WHEN MONTH(created_at) IN (3, 4, 5) THEN 'Spring'
                WHEN MONTH(created_at) IN (6, 7, 8) THEN 'Summer'
                WHEN MONTH(created_at) IN (9, 10, 11) THEN 'Fall'
                WHEN MONTH(created_at) IN (12, 1, 2) THEN 'Winter'
            END AS season,
            YEAR(created_at) AS year,
            SUM(total_price) AS total_revenue
        FROM
            sales_tb
        GROUP BY
            year, season
        ORDER BY
            year, FIELD(season, 'Spring', 'Summer', 'Fall', 'Winter')
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process data for chart
    $seasonData = [
        'Spring' => 0,
        'Summer' => 0,
        'Fall' => 0,
        'Winter' => 0
    ];

    foreach ($results as $row) {
        $seasonData[$row['season']] += $row['total_revenue'];
    }

    // Prepare response
    $response = [
        'success' => true,
        'data' => [
            'Spring' => $seasonData['Spring'],
            'Summer' => $seasonData['Summer'],
            'Fall' => $seasonData['Fall'],
            'Winter' => $seasonData['Winter']
        ]
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    $response = [
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ];
    echo json_encode($response);
}
?>