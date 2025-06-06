<?php
header('Content-Type: application/json');
require_once '../../db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);

try {

    // Validate that price and capital are positive numbers
    if (!is_numeric($data['price']) || $data['price'] <= 0) {
        throw new Exception('Price must be a positive number');
    }

    if (!is_numeric($data['capital']) || $data['capital'] <= 0) {
        throw new Exception('Capital must be a positive number');
    }

    $conn->beginTransaction();
    
    // Update package info
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
        $data['package_id']
    ]);
    
    // Delete existing dishes
    $stmt = $conn->prepare("DELETE FROM menu_package_dishes_tb WHERE package_id = ?");
    $stmt->execute([$data['package_id']]);
    
    // Add new dishes
    foreach ($data['dishes'] as $dish) {
        $stmt = $conn->prepare("
            INSERT INTO menu_package_dishes_tb (package_id, dish_id, quantity)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $data['package_id'],
            $dish['dish_id'],
            $dish['quantity']
        ]);
    }
    
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>