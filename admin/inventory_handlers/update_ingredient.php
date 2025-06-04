<?php
header('Content-Type: application/json');

// Include database connection
include '../../db_connect.php';

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

try {
    // Validate input
    if (empty($data['ingredient_id']) || empty($data['ingredient_name']) || empty($data['quantity']) || empty($data['price'])) {
        throw new Exception('All fields are required except category.');
    }

    // Prepare the SQL statement
    $sql = "UPDATE ingredients_tb SET 
            ingredient_name = :ingredient_name, 
            category = :category, 
            quantity = :quantity, 
            price = :price 
            WHERE ingredient_id = :ingredient_id";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':ingredient_id', $data['ingredient_id']);
    $stmt->bindParam(':ingredient_name', $data['ingredient_name']);
    $stmt->bindParam(':category', $data['category']);
    $stmt->bindParam(':quantity', $data['quantity']);
    $stmt->bindParam(':price', $data['price']);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Ingredient updated successfully']);
    } else {
        throw new Exception('Failed to update ingredient.');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
?>