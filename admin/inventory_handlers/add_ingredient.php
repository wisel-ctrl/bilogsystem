<?php
header('Content-Type: application/json');

require_once '../../db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate data
        if (empty($data['ingredient_name']) || empty($data['price']) || empty($data['quantity'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }
        
        // Insert into database
        $stmt = $conn->prepare("
            INSERT INTO ingredients_tb 
            (ingredient_name, category, price, quantity) 
            VALUES (:name, :category, :price, :quantity)
        ");
        
        $stmt->bindParam(':name', $data['ingredient_name']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':quantity', $data['quantity']);
        
        if ($stmt->execute()) {
            // Get the newly inserted ingredient
            $ingredientId = $conn->lastInsertId();
            $stmt = $conn->prepare("SELECT * FROM ingredients_tb WHERE ingredient_id = ?");
            $stmt->execute([$ingredientId]);
            $ingredient = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'message' => 'Ingredient added successfully', 'data' => $ingredient]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add ingredient']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>