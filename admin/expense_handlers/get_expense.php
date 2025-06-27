<?php
require_once '../../db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $expenseId = $input['id'] ?? '';

    if (empty($expenseId)) {
        echo json_encode([
            'success' => false,
            'message' => 'Expense ID is required'
        ]);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT id, description, category, amount, expense_date, notes 
                               FROM expenses_tb 
                               WHERE id = ? AND status = 1");
        $stmt->execute([$expenseId]);
        $expense = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($expense) {
            echo json_encode([
                'success' => true,
                'expense' => $expense
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Expense not found'
            ]);
        }
    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>