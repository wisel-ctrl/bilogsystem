<?php
require_once '../../db_connection.php';

// Database connection
$pdo = connectDB();

// Get ingredient ID from request
$ingredientId = $_GET['id'] ?? null;

if (!$ingredientId) {
    http_response_code(400);
    echo json_encode(['error' => 'Ingredient ID is required']);
    exit;
}

// Fetch ingredient data
$stmt = $pdo->prepare("SELECT * FROM ingredients_tb WHERE ingredient_id = ?");
$stmt->execute([$ingredientId]);
$ingredient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ingredient) {
    http_response_code(404);
    echo json_encode(['error' => 'Ingredient not found']);
    exit;
}

// Return ingredient data
header('Content-Type: application/json');
echo json_encode($ingredient);
?>