<?php
require_once '../../db_connect.php';

$response = [
    "draw" => intval($_POST['draw'] ?? 1),
    "recordsTotal" => 0,
    "recordsFiltered" => 0,
    "data" => [],
    "error" => null
];

try {
    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $searchValue = $_POST['search']['value'] ?? '';
    $orderColumn = $_POST['order'][0]['column'] ?? 0;
    $orderDir = $_POST['order'][0]['dir'] ?? 'asc';
    $prioritizeOutOfStock = $_POST['prioritizeOutOfStock'] ?? false;

    $columns = [
        0 => 'ingredient_name',
        1 => 'category',
        2 => 'quantity',
        3 => 'price',
        4 => 'total_price'
    ];

    // Base query
    $baseQuery = "SELECT 
        ingredient_id, 
        ingredient_name, 
        category, 
        price, 
        GREATEST(quantity, 0) AS quantity, 
        CASE 
            WHEN quantity < 0 THEN 0 
            ELSE total_price 
        END AS total_price,
        CASE
            WHEN quantity <= 0 THEN 1 ELSE 0
        END AS is_out_of_stock
    FROM ingredients_tb 
    WHERE visibility = 'show'";

    $filteredQuery = $baseQuery;
    
    if (!empty($searchValue)) {
        $filteredQuery .= " AND (ingredient_name LIKE :search OR category LIKE :search)";
    }

    // Get total records
    $totalRecords = $conn->query("SELECT COUNT(*) FROM ingredients_tb WHERE visibility = 'show'")->fetchColumn();

    // Get filtered records count
    $stmtCount = $conn->prepare("SELECT COUNT(*) FROM ingredients_tb WHERE visibility = 'show'" . 
                               (!empty($searchValue) ? " AND (ingredient_name LIKE :search OR category LIKE :search)" : ""));
    
    if (!empty($searchValue)) {
        $searchParam = "%$searchValue%";
        $stmtCount->bindParam(':search', $searchParam, PDO::PARAM_STR);
    }
    $stmtCount->execute();
    $filteredRecords = $stmtCount->fetchColumn();

    // Modify ordering if we're prioritizing out-of-stock items
    $orderColumnName = $columns[$orderColumn] ?? $columns[0];
    
    if ($prioritizeOutOfStock && $orderColumnName === 'quantity') {
        // First sort by is_out_of_stock (1 comes first), then by quantity
        $filteredQuery .= " ORDER BY is_out_of_stock DESC, $orderColumnName $orderDir";
    } else {
        $filteredQuery .= " ORDER BY $orderColumnName $orderDir";
    }

    $filteredQuery .= " LIMIT :start, :length";

    $stmt = $conn->prepare($filteredQuery);

    if (!empty($searchValue)) {
        $searchParam = "%$searchValue%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    }

    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':length', $length, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = [
        "draw" => intval($_POST['draw'] ?? 1),
        "recordsTotal" => intval($totalRecords),
        "recordsFiltered" => intval($filteredRecords),
        "data" => $data,
        "error" => null
    ];

} catch (PDOException $e) {
    $response['error'] = "Database error: " . $e->getMessage();
} catch (Exception $e) {
    $response['error'] = "Error: " . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>