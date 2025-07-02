<?php
require_once '../../db_connect.php';
date_default_timezone_set('Asia/Manila');

$requestData = $_REQUEST;

$columns = array(
    0 => 'customer_name',
    1 => 'contact_number',
    2 => 'package_name',
    3 => 'pax',
    4 => 'reservation_datetime',
    5 => 'booking_id'
);

$status = $_POST['status'] ?? 'pending';

// Base SQL query
$baseQuery = "SELECT 
    b.booking_id,
    mp.package_name,
    b.pax,
    b.totalPrice,
    b.reservation_datetime,
    b.notes,
    COALESCE(CONCAT(u.first_name, ' ', 
                COALESCE(u.middle_name, ''), 
                CASE WHEN u.middle_name IS NOT NULL THEN ' ' ELSE '' END,
                u.last_name, 
                CASE WHEN u.suffix IS NOT NULL THEN CONCAT(' ', u.suffix) ELSE '' END), '') AS customer_name,
    u.contact_number
FROM booking_tb AS b
JOIN menu_packages_tb AS mp ON b.package_id = mp.package_id
JOIN users_tb AS u ON b.customer_id = u.id
WHERE b.booking_status = :status";

$params = [':status' => $status];

// Count total records without filtering
$totalQuery = "SELECT COUNT(*) FROM booking_tb WHERE booking_status = :status";
$stmtTotal = $conn->prepare($totalQuery);
$stmtTotal->execute([':status' => $status]);
$totalRecords = $stmtTotal->fetchColumn();

// Search filter
$searchClause = "";
if (!empty($requestData['search']['value'])) {
    $search = "%" . $requestData['search']['value'] . "%";
    $searchClause = " AND (
        CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name) LIKE :search 
        OR u.contact_number LIKE :search 
        OR mp.package_name LIKE :search
    )";
    $params[':search'] = $search;
}

// Total filtered records
$filterQuery = "SELECT COUNT(*) FROM booking_tb AS b
JOIN menu_packages_tb AS mp ON b.package_id = mp.package_id
JOIN users_tb AS u ON b.customer_id = u.id
WHERE b.booking_status = :status $searchClause";

$stmtFiltered = $conn->prepare($filterQuery);
$stmtFiltered->execute($params);
$totalFiltered = $stmtFiltered->fetchColumn();

// Ordering
$orderSql = " ORDER BY b.reservation_datetime DESC";
if (isset($requestData['order'][0]['column'])) {
    $orderColumnIndex = $requestData['order'][0]['column'];
    $orderDir = $requestData['order'][0]['dir'] === 'asc' ? 'ASC' : 'DESC';
    $orderColumn = $columns[$orderColumnIndex] ?? 'reservation_datetime';

    // Safe column name mapping
    switch ($orderColumn) {
        case 'customer_name': $orderColumn = "u.first_name"; break;
        case 'contact_number': $orderColumn = "u.contact_number"; break;
        case 'package_name': $orderColumn = "mp.package_name"; break;
        case 'pax': $orderColumn = "b.pax"; break;
        case 'reservation_datetime': $orderColumn = "b.reservation_datetime"; break;
        case 'booking_id': $orderColumn = "b.booking_id"; break;
        default: $orderColumn = "b.reservation_datetime";
    }
    $orderSql = " ORDER BY $orderColumn $orderDir";
}

// Pagination
$limitSql = "";
if ($requestData['length'] != -1) {
    $limitSql = " LIMIT :start, :length";
    $params[':start'] = (int)$requestData['start'];
    $params[':length'] = (int)$requestData['length'];
}

// Final data query
$finalQuery = $baseQuery . $searchClause . $orderSql . $limitSql;
$stmtData = $conn->prepare($finalQuery);

// Bind values explicitly for LIMIT (they must be integers)
foreach ($params as $key => $val) {
    if (in_array($key, [':start', ':length'])) {
        $stmtData->bindValue($key, $val, PDO::PARAM_INT);
    } else {
        $stmtData->bindValue($key, $val);
    }
}

$stmtData->execute();

$data = [];
while ($row = $stmtData->fetch(PDO::FETCH_ASSOC)) {
    $nestedData = array(
        'customer_name' => $row['customer_name'],
        'contact_number' => $row['contact_number'],
        'package_name' => $row['package_name'],
        'pax' => $row['pax'],
        'reservation_datetime' => $row['reservation_datetime'],
        'booking_id' => $row['booking_id'],
        'totalPrice' => $row['totalPrice'],
        'notes' => $row['notes']
    );
    $data[] = $nestedData;
}

// Final JSON response
$json_data = array(
    "draw" => intval($requestData['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
);

echo json_encode($json_data);
?>
