<?php
header('Content-Type: application/json');
require_once '../../db_connect.php';

$packageId = $_GET['id'] ?? 0;

try {
    // Get package basic info
    $stmt = $conn->prepare("SELECT * FROM menu_packages_tb WHERE package_id = ?");
    $stmt->execute([$packageId]);
    $package = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$package) {
        throw new Exception("Package not found");
    }
    
    // Get dishes in this package
    $stmt = $conn->prepare("
        SELECT m.dish_id, m.quantity, d.dish_name, d.dish_category, d.price, d.capital 
        FROM menu_package_dishes_tb m
        JOIN dishes_tb d ON m.dish_id = d.dish_id
        WHERE m.package_id = ?
    ");
    $stmt->execute([$packageId]);
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $package['dishes'] = $dishes;
    
    echo json_encode($package);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>