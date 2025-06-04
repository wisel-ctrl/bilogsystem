<?php
require_once '../../db_connect.php';


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

// Base query
$query = "SELECT SQL_CALC_FOUND_ROWS ingredient_id, ingredient_name, category, price, quantity, total_price 
          FROM ingredients_tb 
          WHERE 1=1";

// Add search condition if search value is provided
if (!empty($searchValue)) {
    $query .= " AND (ingredient_name LIKE :search OR category LIKE :search)";
}

// Add ordering
$orderColumnName = $columns[$orderColumn] ?? $columns[0];
$query .= " ORDER BY $orderColumnName $orderDir";

// Add limit for pagination
$query .= " LIMIT :start, :length";

// Prepare and execute the query
$stmt = $pdo->prepare($query);

if (!empty($searchValue)) {
    $searchParam = "%$searchValue%";
    $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
}

$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':length', $length, PDO::PARAM_INT);
$stmt->execute();

// Fetch the data
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total records and filtered records
$totalRecords = $pdo->query("SELECT COUNT(*) FROM ingredients_tb")->fetchColumn();
$filteredRecords = $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();

// Prepare response for DataTables
$response = [
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($filteredRecords),
    "data" => $data
];

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>