<?php

require_once '../admin_auth.php';
require_once '../../db_connect.php'; // Make sure this includes your PDO connection

date_default_timezone_set('Asia/Manila');

// Check if the request is POST and user is logged in
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    header('HTTP/1.1 403 Forbidden');
    exit(json_encode(['success' => false, 'message' => 'Access denied']));
}

// Get the booking ID and reason from the POST data
$bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
$reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
$user_id = $_SESSION['user_id'];

// Validate inputs
if ($bookingId <= 0) {
    header('HTTP/1.1 400 Bad Request');
    exit(json_encode(['success' => false, 'message' => 'Invalid booking ID']));
}

if (strlen($reason) < 10) {
    header('HTTP/1.1 400 Bad Request');
    exit(json_encode(['success' => false, 'message' => 'Reason must be at least 10 characters']));
}

try {

    $currentDateTime = date('Y-m-d H:i:s');
    // Prepare the update query using PDO
    $stmt = $conn->prepare("UPDATE booking_tb 
                          SET booking_status = 'declined', 
                              decline_reason = :reason,
                              response_id = :user_id,
                              acceptdecline_datetime = :datetime 
                          WHERE booking_id = :bookingId");
    
    // Bind parameters and execute
    $stmt->execute([
        ':reason' => $reason,
        ':datetime' => $currentDateTime,
        ':user_id' => $user_id,
        ':bookingId' => $bookingId
    ]);
    
    // Check if the update was successful
    if ($stmt->rowCount() > 0) {
        // Success response
        echo json_encode([
            'success' => true, 
            'message' => 'Booking declined successfully',
            'booking_id' => $bookingId
        ]);
    } else {
        // No rows affected - booking might not exist
        echo json_encode([
            'success' => false, 
            'message' => 'Booking not found or no changes made'
        ]);
    }
    
} catch (PDOException $e) {
    // Database error
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode([
        'success' => false, 
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>