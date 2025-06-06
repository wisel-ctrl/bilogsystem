<?php
header('Content-Type: application/json');

// Database connection
require_once '../../db_connect.php';

$response = ['success' => false, 'message' => ''];

try {
    // Get the JSON data from the request body
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        throw new Exception('Invalid input data');
    }
    
    // Validate required fields
    if (empty($data['name']) || empty($data['price']) || empty($data['capital']) || empty($data['type'])) {
        throw new Exception('Required fields are missing');
    }
    
    // Start transaction
    $conn->beginTransaction();
    
    // Insert into menu_packages
    $stmt = $conn->prepare("INSERT INTO menu_packages 
                           (package_name, package_description, price, capital, type) 
                           VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt->execute([
        $data['name'],
        $data['description'] ?? null,
        $data['price'],
        $data['capital'],
        $data['type']
    ])) {
        throw new Exception('Failed to create package');
    }
    
    $package_id = $conn->lastInsertId();
    
    // Insert dish mappings if there are any
    if (!empty($data['dishes']) && is_array($data['dishes'])) {
        $dish_stmt = $conn->prepare("INSERT INTO menu_package_dishes_tb 
                                    (package_id, dish_id, quantity) 
                                    VALUES (?, ?, ?)");
        
        foreach ($data['dishes'] as $dish) {
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