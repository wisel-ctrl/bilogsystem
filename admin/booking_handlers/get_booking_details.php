<?php
require_once '../..//db_connect.php';

if (isset($_GET['booking_id'])) {
    $bookingId = $_GET['booking_id'];
    
    try {
        $conn = connectDatabase($dsn, $pdoOptions);
        
        $sql = "SELECT 
                    b.booking_id,
                    b.package_id,
                    mp.package_name,
                    b.pax,
                    b.event,
                    b.event_hall,
                    b.totalPrice,
                    b.reservation_datetime,
                    b.notes,
                    b.downpayment_img,
                    b.customer_id,
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
                    u.contact_number,
                    b.booking_status,
                    b.booking_datetime
                FROM booking_tb AS b
                JOIN menu_packages_tb AS mp ON b.package_id = mp.package_id
                JOIN users_tb AS u ON b.customer_id = u.id
                WHERE b.booking_id = :booking_id";
                
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Calculate booking age (time since booking was made)
            $bookingDateTime = new DateTime($result['booking_datetime']);
            $currentDateTime = new DateTime();
            $interval = $currentDateTime->diff($bookingDateTime);
            
            $bookingAge = '';
            if ($interval->y > 0) {
                $bookingAge = $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
            } elseif ($interval->m > 0) {
                $bookingAge = $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
            } elseif ($interval->d > 0) {
                $bookingAge = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
            } elseif ($interval->h > 0) {
                $bookingAge = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
            } else {
                $bookingAge = 'Less than an hour ago';
            }
            
            // Format the response
            $response = [
                'success' => true,
                'data' => [
                    'booking_id' => $result['booking_id'],
                    'booking_age' => $bookingAge,
                    'customer_name' => $result['customer_name'],
                    'contact_number' => $result['contact_number'],
                    'event_hall' => $result['event_hall'],
                    'event' => $result['event'],
                    'package_name' => $result['package_name'],
                    'pax' => $result['pax'],
                    'totalPrice' => number_format($result['totalPrice'], 2),
                    'reservation_datetime' => date('F j, Y, g:i a', strtotime($result['reservation_datetime'])),
                    'notes' => $result['notes'] ? $result['notes'] : 'No notes provided',
                    'downpayment_img' => $result['downpayment_img']
                ]
            ];
            
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Booking not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Booking ID not provided']);
}
?>