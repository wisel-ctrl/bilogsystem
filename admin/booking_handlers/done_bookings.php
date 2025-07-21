<?php
header('Content-Type: application/json');

require_once '../../db_connect.php'; // Adjust path as needed

try {
    // Prepare SQL query
    $sql = "
        SELECT 
            b.booking_id,
            b.pax,
            b.event,
            b.event_hall,
            b.totalPrice,
            b.reservation_datetime,
            CONCAT_WS(' ',
                COALESCE(u.first_name, ''),
                COALESCE(u.middle_name, ''),
                COALESCE(u.last_name, ''),
                COALESCE(u.suffix, '')
            ) AS customer_name,
            mp.package_name
        FROM 
            booking_tb b
        JOIN 
            users_tb u ON b.customer_id = u.id
        LEFT JOIN 
            menu_packages_tb mp ON b.package_id = mp.package_id
        WHERE 
            b.booking_status = 'done'
    ";

    // Execute query
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode([
        'status' => 'success',
        'data' => $results
    ]);

} catch (PDOException $e) {
    // Return error message in JSON format
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
