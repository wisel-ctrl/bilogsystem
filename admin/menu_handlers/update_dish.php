<?php
header('Content-Type: application/json');
require_once '../../db_connect.php';

try {
    // Start transaction
    $pdo->beginTransaction();

    // Get form data
    $dishId = $_POST['dish_id'] ?? null;
    $name = $_POST['name'] ?? null;
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? null;
    $price = $_POST['price'] ?? null;
    $capital = $_POST['capital'] ?? null;
    $status = $_POST['status'] ?? 'available';
    $ingredients = json_decode($_POST['ingredients'], true) ?? [];

    // Validate required fields
    if (!$dishId || !$name || !$category || !$price || !$capital) {
        throw new Exception("All required fields must be filled");
    }

    // Handle image upload if exists
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../../assets/images/dishes/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Get current image path to delete later
        $stmt = $pdo->prepare("SELECT dish_pic_url FROM dishes_tb WHERE dish_id = ?");
        $stmt->execute([$dishId]);
        $currentImage = $stmt->fetchColumn();

        // Generate unique filename
        $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'dish_' . uniqid() . '.' . $fileExt;
        $targetPath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = 'assets/images/dishes/' . $filename;
            
            // Delete old image if exists
            if ($currentImage && file_exists('../../' . $currentImage)) {
                unlink('../../' . $currentImage);
            }
        } else {
            throw new Exception("Failed to upload image");
        }
    }

    // Update dish information
    $updateDishSql = "UPDATE dishes_tb SET 
        dish_name = ?, 
        dish_description = ?, 
        dish_category = ?, 
        price = ?, 
        capital = ?, 
        status = ?" . 
        ($imagePath ? ", dish_pic_url = ?" : "") . 
        " WHERE dish_id = ?";

    $params = [$name, $description, $category, $price, $capital, $status];
    if ($imagePath) {
        $params[] = $imagePath;
    }
    $params[] = $dishId;

    $stmt = $pdo->prepare($updateDishSql);
    $stmt->execute($params);

    // Update ingredients - first delete existing ones, then insert new ones
    $deleteIngredientsSql = "DELETE FROM dish_ingredients WHERE dish_id = ?";
    $stmt = $pdo->prepare($deleteIngredientsSql);
    $stmt->execute([$dishId]);

    if (!empty($ingredients)) {
        $insertIngredientSql = "INSERT INTO dish_ingredients (dish_id, ingredient_id, quantity_grams) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($insertIngredientSql);
        
        foreach ($ingredients as $ingredient) {
            $stmt->execute([
                $dishId,
                $ingredient['ingredient_id'],
                $ingredient['quantity_grams']
            ]);
        }
    }

    // Commit transaction
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Dish updated successfully'
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>