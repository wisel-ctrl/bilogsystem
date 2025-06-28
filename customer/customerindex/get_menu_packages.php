<?php
require_once '../../db_connect.php';

try {
    $query = "SELECT package_id, package_name, package_description, price, type, status 
              FROM menu_packages_tb 
              WHERE status = 'active' 
              ORDER BY type, package_name";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $menu_packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the response
    $response = [
        'status' => 'success',
        'data' => $menu_packages
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Error fetching menu packages: ' . $e->getMessage()
    ]);
}

// Close the statement and connection
$stmt = null;
$conn = null;
?>