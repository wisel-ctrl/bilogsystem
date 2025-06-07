<?php
header('Content-Type: application/json');

// Database connection
require_once '../../db_connect.php';

try {
    $query = "SELECT package_id, package_name, price, type, status FROM `menu_packages_tb`";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($packages);
    
} catch(PDOException $e) {
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>