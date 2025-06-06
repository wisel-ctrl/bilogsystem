<?php
require_once '../../db_connect.php';

// Initialize response array with error field
$response = [
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => 0,
    "recordsFiltered" => 0,
    "data" => [],
    "error" => null
];

try {
    // Get parameters from DataTables request
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $searchValue = $_POST['search']['value'] ?? '';
    $orderColumn = $_POST['order'][0]['column'] ?? 0;
    $orderDir = $_POST['order'][0]['dir'] ?? 'asc';

    // Column mapping for ordering
    $columns = [
        0 => 'ingredient_name',
        1 => 'category',
        2 => 'quantity',
        3 => 'price',
        4 => 'total_price'
    ];

    // Base query for total records (without LIMIT)
    $baseQuery = "SELECT ingredient_id, ingredient_name, category, price, quantity, total_price 
              FROM ingredients_tb 
              WHERE visibility = 'show'";

    // Query for filtered records (with search conditions if any)
    $filteredQuery = $baseQuery;
    
    // Add search condition if search value is provided
    if (!empty($searchValue)) {
        $filteredQuery .= " AND (ingredient_name LIKE :search OR category LIKE :search)";
    }

    // Get total records (all visible records)
    $totalRecords = $conn->query("SELECT COUNT(*) FROM ingredients_tb WHERE visibility = 'show'")->fetchColumn();

    // Get filtered records count (with search conditions if any)
    $stmtCount = $conn->prepare("SELECT COUNT(*) FROM ingredients_tb WHERE visibility = 'show'" . 
                               (!empty($searchValue) ? " AND (ingredient_name LIKE :search OR category LIKE :search)" : ""));
    
    if (!empty($searchValue)) {
        $searchParam = "%$searchValue%";
        $stmtCount->bindParam(':search', $searchParam, PDO::PARAM_STR);
    }
    $stmtCount->execute();
    $filteredRecords = $stmtCount->fetchColumn();

    // Add ordering to data query
    $orderColumnName = $columns[$orderColumn] ?? $columns[0];
    $filteredQuery .= " ORDER BY $orderColumnName $orderDir";

    // Add limit for pagination
    $filteredQuery .= " LIMIT :start, :length";

    // Prepare and execute the query for data
    $stmt = $conn->prepare($filteredQuery);

    if (!empty($searchValue)) {
        $searchParam = "%$searchValue%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    }

    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':length', $length, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Update successful response
    $response = [
        "draw" => intval($_POST['draw'] ?? 1),
        "recordsTotal" => intval($totalRecords),
        "recordsFiltered" => intval($filteredRecords),
        "data" => $data,
        "error" => null
    ];

} catch (PDOException $e) {
    // Database related errors
    $response['error'] = "Database error: " . $e->getMessage();
} catch (Exception $e) {
    // All other errors
    $response['error'] = "Error: " . $e->getMessage();
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>