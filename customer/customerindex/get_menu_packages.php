<?php
require_once '../../db_connect.php';

try {
    $query = "SELECT package_id, package_name, package_description, price, type, status, image_path 
              FROM menu_packages_tb 
              WHERE status = 'active' 
              ORDER BY type, package_name"; // Limit to 2 for Special Offers section
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $menu_packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mock discount and rating if not in database (replace with actual columns if available)
    foreach ($menu_packages as &$package) {
        $package['discount'] = $package['package_id'] == 1 ? '20% OFF' : 'SPECIAL'; // Example logic
        $package['rating'] = $package['package_id'] == 1 ? 4.8 : 5.0; // Example rating
    }

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