<?php
header('Content-Type: application/json');
require_once '../../db_connect.php';

$response = ['success' => false, 'message' => ''];

try {
    $packageId = $_GET['id'] ?? 0;

    if (empty($packageId)) {
        throw new Exception('Package ID is required');
    }

    // Get package basic info
    $stmt = $conn->prepare("SELECT package_id, package_name, package_description, price, capital, type, status, image_path 
                           FROM menu_packages_tb 
                           WHERE package_id = ?");
    $stmt->execute([$packageId]);
    $package = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$package) {
        throw new Exception('Package not found');
    }
    
    // Get dishes in this package
    $stmt = $conn->prepare("
        SELECT m.dish_id, m.quantity, d.dish_name, d.dish_category 
        FROM menu_package_dishes_tb m
        JOIN dishes_tb d ON m.dish_id = d.dish_id
        WHERE m.package_id = ?
    ");
    $stmt->execute([$packageId]);
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Prepare response
    $response['success'] = true;
    $response['data'] = [
        'package_id' => $package['package_id'],
        'package_name' => $package['package_name'],
        'package_description' => $package['package_description'],
        'price' => $package['price'],
        'capital' => $package['capital'],
        'type' => $package['type'],
        'status' => $package['status'],
        'image_path' => $package['image_path'],
        'dishes' => $dishes
    ];
    
    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    $response['message'] = $e->getMessage();
    echo json_encode($response);
}
?>