<?php
require_once '../../db_connect.php';

try {
    // Prepare and execute PDO query
    $stmt = $conn->prepare("SELECT DISTINCT package_name FROM menu_packages_tb ORDER BY package_name");
    $stmt->execute();

    // Fetch all package names
    $menus = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Return as JSON
    header('Content-Type: application/json');
    echo json_encode($menus);
} catch (PDOException $e) {
    // Error handling
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
}
?>
