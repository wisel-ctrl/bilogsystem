<?php
header('Content-Type: application/json');
require_once '../../db_connect.php'; // Your database configuration file

try {
    
    $timePeriod = $_GET['period'] ?? 'daily'; // Default to daily
    
    switch ($timePeriod) {
        case 'yearly':
            $currentYear = date('Y');
            $query = "
                WITH years AS (
                    SELECT ".($currentYear-4)." AS year_val UNION ALL
                    SELECT ".($currentYear-3)." UNION ALL
                    SELECT ".($currentYear-2)." UNION ALL
                    SELECT ".($currentYear-1)." UNION ALL
                    SELECT ".$currentYear."
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
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 11 MONTH), '%Y-%m') AS month_val, 
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 11 MONTH), '%M %Y') AS month_name,
                           1 AS month_order UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 10 MONTH), '%Y-%m'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 10 MONTH), '%M %Y'),
                           2 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 9 MONTH), '%Y-%m'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 9 MONTH), '%M %Y'),
                           3 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 8 MONTH), '%Y-%m'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 8 MONTH), '%M %Y'),
                           4 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 7 MONTH), '%Y-%m'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 7 MONTH), '%M %Y'),
                           5 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 6 MONTH), '%Y-%m'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 6 MONTH), '%M %Y'),
                           6 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), '%Y-%m'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), '%M %Y'),
                           7 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 4 MONTH), '%Y-%m'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 4 MONTH), '%M %Y'),
                           8 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH), '%Y-%m'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 MONTH), '%M %Y'),
                           9 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), '%Y-%m'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), '%M %Y'),
                           10 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%M %Y'),
                           11 UNION ALL
                    SELECT DATE_FORMAT(CURDATE(), '%Y-%m'),
                           DATE_FORMAT(CURDATE(), '%M %Y'),
                           12
                )
                SELECT 
                    m.month_name,
                    COALESCE(SUM(s.total_price), 0) AS revenue
                FROM months m
                LEFT JOIN sales_tb s 
                    ON DATE_FORMAT(s.created_at, '%Y-%m') = m.month_val
                GROUP BY m.month_order, m.month_name, m.month_val
                ORDER BY m.month_order;
            ";
            $labelKey = 'month_name';
            break;
            
        case 'daily':
        default:
            $query = "
                WITH days AS (
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 6 DAY), '%Y-%m-%d') AS date_val,
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 6 DAY), '%W') AS day_name,
                           1 AS day_order UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 DAY), '%Y-%m-%d'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 DAY), '%W'),
                           2 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 4 DAY), '%Y-%m-%d'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 4 DAY), '%W'),
                           3 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY), '%Y-%m-%d'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY), '%W'),
                           4 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY), '%Y-%m-%d'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY), '%W'),
                           5 UNION ALL
                    SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d'),
                           DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%W'),
                           6 UNION ALL
                    SELECT DATE_FORMAT(CURDATE(), '%Y-%m-%d'),
                           DATE_FORMAT(CURDATE(), '%W'),
                           7
                )
                SELECT 
                    d.day_name AS weekday,
                    COALESCE(SUM(s.total_price), 0) AS revenue
                FROM days d
                LEFT JOIN sales_tb s 
                    ON DATE(s.created_at) = d.date_val
                GROUP BY d.day_order, d.day_name, d.date_val
                ORDER BY d.day_order;
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