<?php
require_once '../../db_connect.php';

if (!isset($_GET['package_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Package ID is required']);
    exit;
}

$packageId = $_GET['package_id'];

try {
    $stmt = $conn->prepare("
        SELECT 
            mp.package_id,
            mp.package_name,
            mp.package_description,
            mp.price,
            mpd.dish_id,
            mpd.quantity,
            d.dish_name,
            d.dish_category
        FROM 
            menu_packages_tb AS mp
        JOIN 
            menu_package_dishes_tb AS mpd ON mp.package_id = mpd.package_id
        JOIN 
            dishes_tb AS d ON mpd.dish_id = d.dish_id
        WHERE 
            mp.package_id = ?
        ORDER BY 
            FIELD(d.dish_category, 'house-salad', 'spanish-dish', 'italian-dish', 'burgers', 'pizza', 'Pasta', 'pasta_caza', 'main-course', 'drinks', 'coffee', 'desserts'),
            d.dish_name
    ");
    
    $stmt->execute([$packageId]);
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($dishes) {
        echo json_encode([
            'status' => 'success',
            'data' => $dishes
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No dishes found for this package'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>