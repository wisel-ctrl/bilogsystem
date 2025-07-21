<?php
header('Content-Type: application/json');

// Database connection
require_once '../../db_connect.php';

$response = ['success' => false, 'message' => ''];

try {
    // Get form data
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? null;
    $price = floatval($_POST['price'] ?? 0);
    $capital = floatval($_POST['capital'] ?? 0);
    $type = $_POST['type'] ?? '';
    $dishes = json_decode($_POST['dishes'] ?? '[]', true);

    // Validate required fields
    if (empty($name) || $price <= 0 || $capital < 0 || empty($type)) {
        throw new Exception('Required fields are missing');
    }

    // Validate that price and capital are valid
    if (!is_numeric($price) || $price <= 0) {
        throw new Exception('Price must be a positive number');
    }

    if (!is_numeric($capital) || $capital <= 0) {
        throw new Exception('Capital must be a positive number');
    }

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../Uploads/Package_Images/'; // Ensure this directory exists and is writable
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $imageName;

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception('Invalid image format. Only JPEG, PNG, and GIF are allowed.');
        }

        // Validate file size (e.g., max 5MB)
        $maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($_FILES['image']['size'] > $maxSize) {
            throw new Exception('Image file is too large. Maximum size is 5MB.');
        }

        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            throw new Exception('Failed to upload image');
        }
        $imagePath = $targetPath;
    }

    // Start transaction
    $conn->beginTransaction();

    // Insert into menu_packages_tb
    $stmt = $conn->prepare("INSERT INTO menu_packages_tb 
                           (package_name, package_description, price, capital, type, status, image_path) 
                           VALUES (?, ?, ?, ?, ?, 'active', ?)");
    
    if (!$stmt->execute([
        $name,
        $description,
        $price,
        $capital,
        $type,
        $imagePath
    ])) {
        throw new Exception('Failed to create package');
    }
    
    $package_id = $conn->lastInsertId();
    
    // Insert dish mappings if there are any
    if (!empty($dishes) && is_array($dishes)) {
        $dish_stmt = $conn->prepare("INSERT INTO menu_package_dishes_tb 
                                    (package_id, dish_id, quantity) 
                                    VALUES (?, ?, ?)");
        
        foreach ($dishes as $dish) {
            if (empty($dish['dish_id'])) continue;
            
            $quantity = !empty($dish['quantity']) ? (int)$dish['quantity'] : 1;
            
            if (!$dish_stmt->execute([$package_id, $dish['dish_id'], $quantity])) {
                throw new Exception('Failed to add dish to package');
            }
        }
    }
    
    // Commit transaction
    $conn->commit();
    
    $response['success'] = true;
    $response['message'] = 'Package created successfully';
    $response['package_id'] = $package_id;
    
} catch (PDOException $e) {
    // Rollback transaction if there was an error
    if (isset($conn) && $conn instanceof PDO) {
        $conn->rollBack();
    }
    
    $response['message'] = 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    // Rollback transaction if there was an error
    if (isset($conn) && $conn instanceof PDO) {
        $conn->rollBack();
    }
    
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>