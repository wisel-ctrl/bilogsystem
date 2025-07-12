<?php
header('Content-Type: application/json');

require_once("../../db_connect.php");

try {
    // Get DataTables parameters
    $draw = $_GET['draw'] ?? 1;
    $start = $_GET['start'] ?? 0;
    $length = $_GET['length'] ?? 10;
    $searchValue = $_GET['search']['value'] ?? '';
    $orderColumnIndex = $_GET['order'][0]['column'] ?? 0;
    $orderDirection = $_GET['order'][0]['dir'] ?? 'asc';

    // Column mapping
    $columns = [
        0 => 'full_name',
        1 => 'contact_number',
        2 => 'package_name',
        3 => 'pax',
        4 => 'reservation_datetime',
    ];

    $orderColumn = $columns[$orderColumnIndex] ?? 'reservation_datetime';

    // Base query
    $baseQuery = "
        SELECT
            b.booking_id,
            b.pax,
            b.reservation_datetime,
            mp.package_name,
            u.contact_number,
            CONCAT_WS(' ',
                COALESCE(u.first_name, ''),
                COALESCE(u.middle_name, ''),
                COALESCE(u.last_name, ''),
                COALESCE(u.suffix, '')
            ) AS full_name
        FROM booking_tb AS b
        LEFT JOIN menu_packages_tb AS mp ON mp.package_id = b.package_id
        LEFT JOIN users_tb AS u ON u.id = b.customer_id
        WHERE b.booking_status = 'accepted'
    ";

    // Search condition
    $whereClause = '';
    $params = [];
    $types = [];

    if (!empty($searchValue)) {
        $whereClause = " AND (
            CONCAT_WS(' ', 
                COALESCE(u.first_name, ''),
                COALESCE(u.middle_name, ''),
                COALESCE(u.last_name, ''),
                COALESCE(u.suffix, '')
            ) LIKE :search OR
            u.contact_number LIKE :search OR
            mp.package_name LIKE :search OR
            b.pax LIKE :search OR
            b.reservation_datetime LIKE :search
        )";
        $params[':search'] = '%' . $searchValue . '%';
        $types[':search'] = PDO::PARAM_STR;
    }

    // Count total records
    $countQuery = "SELECT COUNT(*) AS total FROM booking_tb WHERE booking_status = 'accepted'";
    $stmt = $conn->prepare($countQuery);
    $stmt->execute();
    $totalRecords = $stmt->fetchColumn();

    // Count filtered records
    $countFilteredQuery = "SELECT COUNT(*) FROM ($baseQuery $whereClause) AS derived_table";
    $stmt = $conn->prepare($countFilteredQuery);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, $types[$key] ?? PDO::PARAM_STR);
    }
    $stmt->execute();
    $filteredRecords = $stmt->fetchColumn();

    // Data query
    $dataQuery = "
        $baseQuery
        $whereClause
        ORDER BY $orderColumn $orderDirection
        LIMIT :start, :length
    ";

    $stmt = $conn->prepare($dataQuery);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, $types[$key] ?? PDO::PARAM_STR);
    }
    
    $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
    $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);
    
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = [
        "draw" => intval($draw),
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $filteredRecords,
        "data" => $data
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "error" => "Database error",
        "message" => $e->getMessage()
    ]);
}