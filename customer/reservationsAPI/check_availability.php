<?php
header('Content-Type: application/json');
require_once '../../db_connect.php';

$input = json_decode(file_get_contents('php://input'), true);
$reservationDateTime = $input['reservation_datetime'] ?? null;

if (!$reservationDateTime) {
    echo json_encode(['available' => false, 'message' => 'No date provided']);
    exit;
}

try {
    // Calculate total pax for the given datetime (within a 3-hour window)
    $startTime = date('Y-m-d H:i:s', strtotime($reservationDateTime . ' -3 hours'));
    $endTime = date('Y-m-d H:i:s', strtotime($reservationDateTime . ' +3 hours'));
    
    $stmt = $conn->prepare("
        SELECT SUM(pax) as total_pax 
        FROM booking_tb 
        WHERE reservation_datetime BETWEEN :start_time AND :end_time
        AND booking_status = 'accepted'
    ");
    
    $stmt->execute([
        ':start_time' => $startTime,
        ':end_time' => $endTime
    ]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalPax = $result['total_pax'] ?? 0;
    
    $available = $totalPax < 95;
    
    echo json_encode([
        'available' => $available,
        'total_pax' => $totalPax,
        'message' => $available 
            ? 'Slot available' 
            : 'This time slot is fully booked. Please choose another time.'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'available' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>