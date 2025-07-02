<?php
require_once '../../db_connect.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

// Database connection
$db = new DB_CONNECT();

// Get the request parameters for DataTables
$requestData = $_REQUEST;

// Columns to be displayed
$columns = array(
    0 => 'customer_name',
    1 => 'contact_number',
    2 => 'package_name',
    3 => 'pax',
    4 => 'reservation_datetime',
    5 => 'booking_id'
);

// Base query
$query = "SELECT 
    b.booking_id,
    mp.package_name,
    b.pax,
    b.totalPrice,
    b.reservation_datetime,
    b.notes,
    COALESCE(CONCAT(u.first_name, ' ', 
                COALESCE(u.middle_name, ''), 
                CASE 
                    WHEN u.middle_name IS NOT NULL THEN ' ' 
                    ELSE '' 
                END,
                u.last_name, 
                CASE 
                    WHEN u.suffix IS NOT NULL THEN CONCAT(' ', u.suffix) 
                    ELSE '' 
                END), '') AS customer_name,
    u.contact_number
FROM booking_tb AS b
JOIN menu_packages_tb AS mp ON b.package_id = mp.package_id
JOIN users_tb AS u ON b.customer_id = u.id
WHERE b.booking_status = ?";

// Parameters
$params = array($_POST['status']);

// Total records without filtering
$totalRecords = $db->query("SELECT COUNT(*) as total FROM booking_tb WHERE booking_status = ?", $params)->fetchArray()['total'];

// Search functionality
if (!empty($requestData['search']['value'])) {
    $searchValue = $requestData['search']['value'];
    $query .= " AND (customer_name LIKE ? OR contact_number LIKE ? OR package_name LIKE ?)";
    array_push($params, "%$searchValue%", "%$searchValue%", "%$searchValue%");
}

// Total records with filtering
$totalFiltered = $db->query(str_replace("SELECT b.booking_id, mp.package_name", "SELECT COUNT(*) as total", $query), $params)->fetchArray()['total'];

// Ordering
if (isset($requestData['order'][0]['column'])) {
    $orderColumn = $columns[$requestData['order'][0]['column']];
    $orderDirection = $requestData['order'][0]['dir'];
    $query .= " ORDER BY $orderColumn $orderDirection";
} else {
    $query .= " ORDER BY b.reservation_datetime DESC";
}

// Pagination
if (isset($requestData['start']) && $requestData['length'] != -1) {
    $query .= " LIMIT ?, ?";
    array_push($params, intval($requestData['start']), intval($requestData['length']));
}

// Execute the query
$result = $db->query($query, $params);

// Prepare the data
$data = array();
while ($row = $result->fetchArray()) {
    $nestedData = array();
    $nestedData['customer_name'] = $row['customer_name'];
    $nestedData['contact_number'] = $row['contact_number'];
    $nestedData['package_name'] = $row['package_name'];
    $nestedData['pax'] = $row['pax'];
    $nestedData['reservation_datetime'] = $row['reservation_datetime'];
    $nestedData['booking_id'] = $row['booking_id'];
    $nestedData['totalPrice'] = $row['totalPrice'];
    $nestedData['notes'] = $row['notes'];
    
    $data[] = $nestedData;
}

// Prepare the JSON response
$json_data = array(
    "draw" => intval($requestData['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
);

// Return the JSON response
echo json_encode($json_data);
?>