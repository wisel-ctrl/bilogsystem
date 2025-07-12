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

    // Column mapping - must match the DataTables columns definition
    $columns = [
        0 => 'full_name',
        1 => 'contact_number',
        2 => 'package_name',
        3 => 'pax',
        4 => 'reservation_datetime',
        // 5 is actions column (not searchable/sortable)
    ];

    // Get the column to order by
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

    // Search conditions
    $searchConditions = [];
    $params = [];
    $types = [];

    if (!empty($searchValue)) {
        $searchConditions[] = "(
            full_name LIKE :search OR
            contact_number LIKE :search OR
            package_name LIKE :search OR
            pax LIKE :search OR
            reservation_datetime LIKE :search
        )";
        $params[':search'] = '%' . $searchValue . '%';
        $types[':search'] = PDO::PARAM_STR;
    }

    // Check for date filter (if you implement the date filter input)
    if (!empty($_GET['columns'][4]['search']['value'])) {
        $searchDate = $_GET['columns'][4]['search']['value'];
        $searchConditions[] = "DATE(reservation_datetime) = :search_date";
        $params[':search_date'] = $searchDate;
        $types[':search_date'] = PDO::PARAM_STR;
    }

    // Combine conditions
    $whereClause = '';
    if (!empty($searchConditions)) {
        $whereClause = ' AND ' . implode(' AND ', $searchConditions);
    }

    // Count total records
    $countQuery = "SELECT COUNT(*) AS total FROM ($baseQuery) AS derived_table";
    $stmt = $conn->prepare($countQuery);
    $stmt->execute();
    $totalRecords = $stmt->fetchColumn();

    // Count filtered records
    $countFilteredQuery = "SELECT COUNT(*) AS filtered FROM ($baseQuery $whereClause) AS derived_table";
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
    
    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, $types[$key] ?? PDO::PARAM_STR);
    }
    
    // Bind limit parameters
    $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
    $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);
    
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare response
    $response = [
        "draw" => intval($draw),
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $filteredRecords,
        "data" => $data
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    // Log error (in a real application, you'd want to log this properly)
    error_log("Database error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        "error" => "Database error",
        "message" => $e->getMessage()
    ]);
}