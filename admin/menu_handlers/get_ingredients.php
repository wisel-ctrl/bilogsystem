<?php
header('Content-Type: application/json');

require_once '../../db_connect.php';

try {
    
    $stmt = $conn->prepare("SELECT ingredient_id, ingredient_name FROM ingredients_tb WHERE visibility = 'show' ORDER BY ingredient_name ASC");
    $stmt->execute();
    
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($ingredients);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>