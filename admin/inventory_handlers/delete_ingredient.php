<?php
require_once '../../db_connect.php';


// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (empty($input['ingredient_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ingredient ID is required']);
    exit;
}

try {
    // Delete ingredient
    $stmt = $conn->prepare("UPDATE ingredients_tb SET visibility = 'hidden' WHERE ingredient_id = ?");
    $success = $stmt->execute([$input['ingredient_id']]);
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Ingredient deleted successfully']);
    } else {
        throw new Exception('Failed to delete ingredient');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>