<?php
header('Content-Type: application/json');

// Database connection
require_once '../../db_connect.php'; // Adjust path as needed

try {
    $query = "
    WITH
        this_month_sales AS (
            SELECT sales_id, total_price
            FROM   sales_tb
            WHERE  YEAR(created_at)  = YEAR(CURDATE())
              AND  MONTH(created_at) = MONTH(CURDATE())
        ),
        revenue AS (
            SELECT SUM(total_price) AS revenue
            FROM   this_month_sales
        ),
        other_expenses AS (
            SELECT COALESCE(SUM(amount), 0) AS other_expenses
            FROM   expense_tb
            WHERE  YEAR(created_at)  = YEAR(CURDATE())
              AND  MONTH(created_at) = MONTH(CURDATE())
        ),
        capital_expenses AS (
            SELECT SUM(d.capital * o.quantity) AS capital_expenses
            FROM   this_month_sales  s
            JOIN   order_tb          o ON o.sales_id = s.sales_id
            JOIN   dishes_tb         d ON d.dish_id  = o.dish_id
        )
    SELECT
        COALESCE(r.revenue, 0) AS revenue,
        COALESCE(ce.capital_expenses, 0) AS capital_expenses,
        COALESCE(oe.other_expenses, 0) AS other_expenses,
        (COALESCE(ce.capital_expenses, 0) + COALESCE(oe.other_expenses, 0)) AS total_expenses,
        (COALESCE(r.revenue, 0) - (COALESCE(ce.capital_expenses, 0) + COALESCE(oe.other_expenses, 0))) AS profit
    FROM   revenue          r
    JOIN   capital_expenses ce
    JOIN   other_expenses   oe;
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'revenue' => $result['revenue'],
            'expenses' => $result['total_expenses'],
            'profit' => $result['profit']
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>