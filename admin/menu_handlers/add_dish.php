<?php
header('Content-Type: application/json');

require_once "../../db_connect.php";
try {
    // Start transaction
    $conn->beginTransaction();
    
    // Handle image upload
    $imageUrl = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../images/dish_images/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imageUrl = $targetPath;
        }
    }
    
    // Insert dish into dishes_tb
    $stmt = $conn->prepare("
        INSERT INTO dishes_tb (dish_name, dish_description, dish_category ,price, capital, dish_pic_url) 
        VALUES (:name, :description, :category, :price, :capital, :image_url)
    ");
    
    $stmt->execute([
        ':name' => $_POST['name'],
        ':description' => $_POST['description'] ?? null,
        'category' => $_POST['category'],
        ':price' => $_POST['price'],
        ':capital' => $_POST['capital'],
        ':image_url' => $imageUrl
    ]);
    
    $dishId = $conn->lastInsertId();
    
    // Insert ingredients into dish_ingredients
    $ingredients = json_decode($_POST['ingredients'], true);
    $ingredientStmt = $conn->prepare("
        INSERT INTO dish_ingredients (dish_id, ingredient_id, quantity_kg) 
        VALUES (:dish_id, :ingredient_id, :quantity_kg)
    ");
    
    foreach ($ingredients as $ingredient) {
        $ingredientStmt->execute([
            ':dish_id' => $dishId,
            ':ingredient_id' => $ingredient['ingredient_id'],
            ':quantity_kg' => $ingredient['quantity_kg']
        ]);
    }
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Dish added successfully']);
} catch(PDOException $e) {
    // Rollback transaction on error
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>