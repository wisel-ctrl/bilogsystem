<?php
header('Content-Type: application/json');

require_once "../../db_connect.php";

try {
    // Your query
    $stmt = $conn->prepare("SELECT dish_id, dish_name, dish_category, price, capital, status FROM dishes");
    $stmt->execute();
    
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($dishes);
    
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>