<?php
require_once '../../db_connect.php';

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!$data || !isset($data['total_price'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

try {
    $conn->beginTransaction();
    
    // Insert into sales_tb with payment details
    $stmt = $conn->prepare("
        INSERT INTO sales_tb (
            total_price, 
            discount_type, 
            discount_price, 
            amount_paid,
            amount_change,
            sales_type
        ) VALUES (
            :total_price, 
            :discount_type, 
            :discount_price,
            :amount_paid,
            :amount_change,
            'walk-in'
        )
    ");
    
    $stmt->execute([
        ':total_price' => $data['total_price'],
        ':discount_type' => $data['discount_type'] ?? 'none',
        ':discount_price' => $data['discount_price'] ?? 0,
        ':amount_paid' => $data['amount_paid'],
        ':amount_change' => $data['amount_change']
    ]);
    
    $sales_id = $conn->lastInsertId();
    
    // Insert into order_tb for each item
    $stmt = $conn->prepare("
        INSERT INTO order_tb (sales_id, dish_id, price, quantity) 
        VALUES (:sales_id, :dish_id, :price, :quantity)
    ");
    
    // Prepare statement to get dish ingredients
    $ingredientStmt = $conn->prepare("
        SELECT ingredient_id, quantity_grams 
        FROM dish_ingredients 
        WHERE dish_id = :dish_id
    ");
    
    // Prepare statement to update ingredient quantity
    $updateIngredientStmt = $conn->prepare("
        UPDATE ingredients_tb 
        SET quantity = quantity - (:quantity_kg) 
        WHERE ingredient_id = :ingredient_id
    ");
    
    foreach ($data['items'] as $item) {
        // Insert order record
        $stmt->execute([
            ':sales_id' => $sales_id,
            ':dish_id' => $item['dish_id'],
            ':price' => $item['price'],
            ':quantity' => $item['quantity']
        ]);
        
        // Get all ingredients for this dish
        $ingredientStmt->execute([':dish_id' => $item['dish_id']]);
        $ingredients = $ingredientStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // For each ingredient, calculate total used and update inventory
        foreach ($ingredients as $ingredient) {
            // Calculate total grams used (quantity per dish * number of dishes ordered)
            $total_grams = $ingredient['quantity_grams'] * $item['quantity'];
            
            // Convert to kg (divide by 1000)
            $total_kg = $total_grams / 1000;
            
            // Update ingredient quantity in inventory
            $updateIngredientStmt->execute([
                ':quantity_kg' => $total_kg,
                ':ingredient_id' => $ingredient['ingredient_id']
            ]);
        }
    }
    
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'sales_id' => $sales_id
    ]);
    
} catch (PDOException $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>