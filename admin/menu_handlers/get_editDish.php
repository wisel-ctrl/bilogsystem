<?php
header('Content-Type: application/json');
require_once '../../db_connect.php';

try {
    // Get dish ID from request
    $dishId = $_GET['id'] ?? null;
    if (!$dishId) {
        throw new Exception("Dish ID is required");
    }

    // Fetch dish details
    $stmt = $conn->prepare("
        SELECT dish_id, dish_name, dish_description, dish_category, price, capital, status, dish_pic_url 
        FROM dishes_tb 
        WHERE dish_id = ?
    ");
    $stmt->execute([$dishId]);
    $dish = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dish) {
        throw new Exception("Dish not found");
    }

    // Fetch ingredients for this dish (only visible ingredients)
    $ingredientStmt = $conn->prepare("
        SELECT di.ingredient_id, di.quantity_grams, i.ingredient_name 
        FROM dish_ingredients di
        JOIN ingredients_tb i ON di.ingredient_id = i.ingredient_id
        WHERE di.dish_id = ? AND i.visibility = 'show'
    ");
    $ingredientStmt->execute([$dishId]);
    $ingredients = $ingredientStmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare response
    $response = [
        'success' => true,
        'dish_id' => $dish['dish_id'],
        'dish_name' => $dish['dish_name'],
        'dish_description' => $dish['dish_description'],
        'dish_category' => $dish['dish_category'],
        'price' => $dish['price'],
        'capital' => $dish['capital'],
        'status' => $dish['status'],
        'image_path' => $dish['dish_pic_url'] ? '../../' . $dish['dish_pic_url'] : null,
        'ingredients' => $ingredients
    ];

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>