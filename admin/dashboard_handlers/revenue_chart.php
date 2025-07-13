<?php
header('Content-Type: application/json');
require_once '../../db_connect.php'; // Your database configuration file

try {
    
    $timePeriod = $_GET['period'] ?? 'daily'; // Default to daily
    
    switch ($timePeriod) {
        case 'yearly':
            $query = "
                WITH years AS (
                    SELECT 2021 AS year_val UNION ALL
                    SELECT 2022 UNION ALL
                    SELECT 2023 UNION ALL
                    SELECT 2024 UNION ALL
                    SELECT 2025
                )
                SELECT 
                    y.year_val AS year,
                    COALESCE(SUM(s.total_price), 0) AS revenue
                FROM years y
                LEFT JOIN sales_tb s 
                    ON YEAR(s.created_at) = y.year_val
                GROUP BY y.year_val
                ORDER BY y.year_val;
            ";
            $labelKey = 'year';
            break;
            
        case 'monthly':
            $query = "
                WITH months AS (
                    SELECT 1 AS month_num, 'January' AS month_name UNION ALL
                    SELECT 2, 'February' UNION ALL
                    SELECT 3, 'March' UNION ALL
                    SELECT 4, 'April' UNION ALL
                    SELECT 5, 'May' UNION ALL
                    SELECT 6, 'June' UNION ALL
                    SELECT 7, 'July' UNION ALL
                    SELECT 8, 'August' UNION ALL
                    SELECT 9, 'September' UNION ALL
                    SELECT 10, 'October' UNION ALL
                    SELECT 11, 'November' UNION ALL
                    SELECT 12, 'December'
                )
                SELECT 
                    m.month_name,
                    COALESCE(SUM(s.total_price), 0) AS revenue
                FROM months m
                LEFT JOIN sales_tb s 
                    ON MONTH(s.created_at) = m.month_num
                    AND YEAR(s.created_at) = YEAR(CURDATE())
                GROUP BY m.month_num, m.month_name
                ORDER BY m.month_num;
            ";
            $labelKey = 'month_name';
            break;
            
        case 'daily':
        default:
            $query = "
                WITH weekdays AS (
                    SELECT 'Sunday' AS dayname, 1 AS day_order UNION ALL
                    SELECT 'Monday', 2 UNION ALL
                    SELECT 'Tuesday', 3 UNION ALL
                    SELECT 'Wednesday', 4 UNION ALL
                    SELECT 'Thursday', 5 UNION ALL
                    SELECT 'Friday', 6 UNION ALL
                    SELECT 'Saturday', 7
                )
                SELECT 
                    w.dayname AS weekday,
                    COALESCE(SUM(s.total_price), 0) AS revenue
                FROM weekdays w
                LEFT JOIN sales_tb s 
                    ON DAYOFWEEK(s.created_at) = w.day_order
                GROUP BY w.day_order, w.dayname
                ORDER BY w.day_order;
            ";
            $labelKey = 'weekday';
            break;
    }
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'labels' => array_column($data, $labelKey),
        'revenues' => array_column($data, 'revenue')
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}