<?php
header('Content-Type: application/json');

// Include database connection
require_once("../../db_connect.php");

try {
    // Prepare the SQL query (same as your provided query)
    $sql = "SELECT
        /* ────── 1. Today ────── */
        SUM(CASE WHEN DATE(created_at) = CURDATE() THEN total_price END) AS today_revenue,
        COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) AS today_count,

        /* ────── 2. Yesterday ────── */
        SUM(CASE WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN total_price END) AS yesterday_revenue,

        ROUND(
            (  SUM(CASE WHEN DATE(created_at) = CURDATE() THEN total_price END)
             - SUM(CASE WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN total_price END)
            ) / NULLIF(SUM(CASE WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN total_price END), 0)
            * 100 , 2
        ) AS pct_today_vs_yesterday,

        /* ────── 3. This Week ────── */
        SUM(CASE WHEN YEARWEEK(created_at,1) = YEARWEEK(CURDATE(),1) THEN total_price END) AS this_week_revenue,
        COUNT(CASE WHEN YEARWEEK(created_at,1) = YEARWEEK(CURDATE(),1) THEN 1 END) AS this_week_count,

        SUM(CASE WHEN YEARWEEK(created_at,1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK,1) THEN total_price END) AS last_week_revenue,

        ROUND(
            (  SUM(CASE WHEN YEARWEEK(created_at,1) = YEARWEEK(CURDATE(),1) THEN total_price END)
             - SUM(CASE WHEN YEARWEEK(created_at,1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK,1) THEN total_price END)
            ) / NULLIF(SUM(CASE WHEN YEARWEEK(created_at,1) = YEARWEEK(CURDATE() - INTERVAL 1 WEEK,1) THEN total_price END), 0)
            * 100 , 2
        ) AS pct_this_week_vs_last_week,

        /* ────── 4. This Month ────── */
        SUM(CASE 
                WHEN YEAR(created_at) = YEAR(CURDATE()) 
                 AND MONTH(created_at) = MONTH(CURDATE()) 
            THEN total_price END) AS this_month_revenue,

        COUNT(CASE 
                WHEN YEAR(created_at) = YEAR(CURDATE()) 
                 AND MONTH(created_at) = MONTH(CURDATE()) 
            THEN 1 END) AS this_month_count,

        SUM(CASE 
                WHEN YEAR(created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) 
                 AND MONTH(created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) 
            THEN total_price END) AS last_month_revenue,

        ROUND(
            (  SUM(CASE WHEN YEAR(created_at)=YEAR(CURDATE()) AND MONTH(created_at)=MONTH(CURDATE()) THEN total_price END)
             - SUM(CASE WHEN YEAR(created_at)=YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND MONTH(created_at)=MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) THEN total_price END)
            ) / NULLIF(SUM(CASE WHEN YEAR(created_at)=YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND MONTH(created_at)=MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) THEN total_price END), 0)
            * 100 , 2
        ) AS pct_this_month_vs_last_month,

        /* ────── 5. This Year ────── */
        SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) THEN total_price END) AS this_year_revenue,
        COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) THEN 1 END) AS this_year_count,

        SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) - 1 THEN total_price END) AS last_year_revenue,

        ROUND(
            (  SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) THEN total_price END)
             - SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) - 1 THEN total_price END)
            ) / NULLIF(SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) - 1 THEN total_price END), 0)
            * 100 , 2
        ) AS pct_this_year_vs_last_year

    FROM sales_tb";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch the results
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Format numeric values
        $result['today_revenue'] = (float)$result['today_revenue'];
        $result['yesterday_revenue'] = (float)$result['yesterday_revenue'];
        $result['this_week_revenue'] = (float)$result['this_week_revenue'];
        $result['last_week_revenue'] = (float)$result['last_week_revenue'];
        $result['this_month_revenue'] = (float)$result['this_month_revenue'];
        $result['last_month_revenue'] = (float)$result['last_month_revenue'];
        $result['this_year_revenue'] = (float)$result['this_year_revenue'];
        $result['last_year_revenue'] = (float)$result['last_year_revenue'];

        echo json_encode($result);
    } else {
        echo json_encode(["error" => "No data found"]);
    }
} catch (PDOException $e) {
    // Log error and return JSON error message
    error_log("Database error: " . $e->getMessage());
    echo json_encode(["error" => "Database error occurred"]);
}

// Close connection (not strictly necessary as PDO closes when script ends)
$conn = null;
?>