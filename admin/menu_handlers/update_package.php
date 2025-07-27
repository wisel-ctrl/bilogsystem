<?php
header('Content-Type: application/json');
require_once '../../db_connect.php';

$response = ['success' => false, 'message' => ''];

try {
    // Get form data
    $data = $_POST;
    $package_id = $data['package_id'] ?? null;
    
    // Validate required fields
    if (empty($package_id) || empty($data['name']) || empty($data['price']) || empty($data['capital']) || empty($data['type'])) {
        throw new Exception('Required fields are missing');
    }

    // Validate that price and capital are positive numbers
    if (!is_numeric($data['price']) || $data['price'] <= 0) {
        throw new Exception('Price must be a positive number');
    }

    if (!is_numeric($data['capital']) || $data['capital'] <= 0) {
        throw new Exception('Capital must be a positive number');
    }

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['package_image']) && $_FILES['package_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../Uploads/Package_Images/'; // Ensure this directory exists and is writable
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imageName = uniqid() . '_' . basename($_FILES['package_image']['name']);
        $targetPath = $uploadDir . $imageName;

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['package_image']['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception('Invalid image format. Only JPEG, PNG, and GIF are allowed.');
        }

        // Validate file size (e.g., max 5MB)
        $maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if ($_FILES['package_image']['size'] > $maxSize) {
            throw new Exception('Image file is too large. Maximum size is 5MB.');
        }

        // Move uploaded file
        if (!move_uploaded_file($_FILES['package_image']['tmp_name'], $targetPath)) {
            throw new Exception('Failed to upload image');
        }
        $imagePath = $targetPath;
        
        // Get old image path to delete it later
        $stmt = $conn->prepare("SELECT image_path FROM menu_packages_tb WHERE package_id = ?");
        $stmt->execute([$package_id]);
        $oldImagePath = $stmt->fetchColumn();
    }

    $conn->beginTransaction();
    
    // Update package info - with or without image
    if ($imagePath) {
        $stmt = $conn->prepare("
            UPDATE menu_packages_tb 
            SET package_name = ?, package_description = ?, price = ?, capital = ?, type = ?, status = ?, image_path = ?
            WHERE package_id = ?
        ");
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['capital'],
            $data['type'],
            $data['status'],
            $imagePath,
            $package_id
        ]);
    } else {
        $stmt = $conn->prepare("
            UPDATE menu_packages_tb 
            SET package_name = ?, package_description = ?, price = ?, capital = ?, type = ?, status = ?
            WHERE package_id = ?
        ");
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['capital'],
            $data['type'],
            $data['status'],
            $package_id
        ]);
    }
    
    // Delete existing dishes
    $stmt = $conn->prepare("DELETE FROM menu_package_dishes_tb WHERE package_id = ?");
    $stmt->execute([$package_id]);
    
    // Add new dishes
    $dishes = json_decode($data['dishes'] ?? '[]', true);
    if (!empty($dishes) && is_array($dishes)) {
        foreach ($dishes as $dish) {
            if (empty($dish['dish_id'])) continue;
            
            $quantity = !empty($dish['quantity']) ? (int)$dish['quantity'] : 1;
            
            $stmt = $conn->prepare("
                INSERT INTO menu_package_dishes_tb (package_id, dish_id, quantity)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([
                $package_id,
                $dish['dish_id'],
                $quantity
            ]);
        }
    }
    
    $conn->commit();
    
    // Delete old image after successful update
    if (isset($oldImagePath) && $oldImagePath && file_exists($oldImagePath)) {
        unlink($oldImagePath);
    }
    
    $response['success'] = true;
    $response['message'] = 'Package updated successfully';
    
} catch (PDOException $e) {
    $conn->rollBack();
    $response['message'] = 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    $conn->rollBack();
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>