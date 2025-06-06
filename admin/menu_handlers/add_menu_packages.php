<?php
header('Content-Type: application/json');

// Database connection
require_once '../../db_connect.php'; // Assuming you have a file with database connection

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
    $conn->begin_transaction();
    
    // Insert into menu_packages_tb
    $stmt = $conn->prepare("INSERT INTO menu_packages_tb 
                           (package_name, package_description, price, capital, type) 
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdds", 
        $data['name'], 
        $data['description'], 
        $data['price'], 
        $data['capital'], 
        $data['type']
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to create package: ' . $stmt->error);
    }
    
    $package_id = $conn->insert_id;
    $stmt->close();
    
    // Insert dish mappings if there are any
    if (!empty($data['dishes']) && is_array($data['dishes'])) {
        $dish_stmt = $conn->prepare("INSERT INTO menu_package_dishes_tb 
                                    (package_id, dish_id, quantity) 
                                    VALUES (?, ?, ?)");
        
        foreach ($data['dishes'] as $dish) {
            if (empty($dish['dish_id'])) continue;
            
            $quantity = !empty($dish['quantity']) ? (int)$dish['quantity'] : 1;
            $dish_stmt->bind_param("iii", $package_id, $dish['dish_id'], $quantity);
            
            if (!$dish_stmt->execute()) {
                throw new Exception('Failed to add dish to package: ' . $dish_stmt->error);
            }
        }
        
        $dish_stmt->close();
    }
    
    // Commit transaction
    $conn->commit();
    
    $response['success'] = true;
    $response['message'] = 'Package created successfully';
    $response['package_id'] = $package_id;
    
} catch (Exception $e) {
    // Rollback transaction if there was an error
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->rollback();
    }
    
    $response['message'] = $e->getMessage();
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}

echo json_encode($response);
?>