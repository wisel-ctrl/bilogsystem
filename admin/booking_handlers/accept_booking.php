<?php
require_once '../../db_connect.php'; // Make sure this file contains your PDO connection
require_once '../admin_auth.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

try {
    // Get the booking ID from the POST request
    $bookingId = $_POST['booking_id'] ?? null;

    if (!$bookingId) {
        throw new Exception('Booking ID is required');
    }

    // Get the user_id from the session
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        throw new Exception('User not authenticated');
    }

    // First, get the customer's phone number and name from the database
    $stmt = $conn->prepare("
        SELECT u.contact_number, CONCAT(u.first_name, ' ', u.last_name) AS customer_name, b.reservation_datetime 
        FROM booking_tb b
        JOIN users_tb u ON b.customer_id = u.id
        WHERE b.booking_id = :booking_id
    ");
    $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
    $bookingDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bookingDetails) {
        throw new Exception('Booking details not found');
    }

    $phoneNumber = $bookingDetails['contact_number'];
    $customerName = $bookingDetails['customer_name'];
    $reservationDate = date('F j, Y h:i A', strtotime($bookingDetails['reservation_datetime']));

    // Get current datetime in Asia/Manila timezone
    $currentDateTime = date('Y-m-d H:i:s');

    // Prepare the SQL query to update the booking status, response_id, and acceptdecline_datetime
    $stmt = $conn->prepare("
        UPDATE booking_tb 
        SET 
            booking_status = 'accepted',
            response_id = :user_id,
            acceptdecline_datetime = :current_datetime
        WHERE booking_id = :booking_id
    ");
    
    $stmt->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':current_datetime', $currentDateTime);
    $stmt->execute();

    // Check if any row was actually updated
    if ($stmt->rowCount() === 0) {
        throw new Exception('No booking found with that ID');
    }

    // Send SMS notification
    $message = "Hello $customerName, your booking for $reservationDate has been accepted. Thank you for choosing Caffe Lilio!";
    sendSMS($phoneNumber, $message);
    
            // Create notification for the customer
    createNotification($conn, $userId, $bookingId, 'accepted');

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Booking has been accepted!'
    ]);
    


} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function sendSMS($number, $message) {
    $apiKey = '487b60aae3df89ca35dc3b4dd69e2518';
    $senderName = 'CaffeLilio';
    
    // Clean the phone number (remove + and any non-numeric characters)
    $number = preg_replace('/[^0-9]/', '', $number);
    
    // Add country code if not present (assuming Philippines)
    if (strlen($number) === 10 && substr($number, 0, 1) === '9') {
        $number = '63' . $number;
    } elseif (strlen($number) === 11 && substr($number, 0, 2) === '09') {
        $number = '63' . substr($number, 1);
    }
    
    $url = "https://api.semaphore.co/api/v4/messages";
    $data = [
        'apikey' => $apiKey,
        'number' => $number,
        'message' => $message,
        'sendername' => $senderName
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    @file_get_contents($url, false, $context);
}

function createNotification($conn, $user_id, $booking_id, $status, $reason = null) {
    // Get booking details for the notification message
    $stmt = $conn->prepare("
        SELECT b.customer_id, b.reservation_datetime, u.first_name, u.last_name
        FROM booking_tb b
        JOIN users_tb u ON b.customer_id = u.id
        WHERE b.booking_id = :booking_id
    ");
    $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
    $stmt->execute();
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$booking) return false;

    $customer_id = $booking['customer_id'];
    $reservationDate = date('F j, Y h:i A', strtotime($booking['reservation_datetime']));
    $customerName = $booking['first_name'] . ' ' . $booking['last_name'];

    // Create appropriate message based on status
    if ($status === 'accepted') {
        $message = "Your booking #$booking_id for $reservationDate has been accepted. Thank you, $customerName!";
    } else {
        $reasonText = $reason ? " Reason: $reason" : "";
        $message = "Your booking #$booking_id for $reservationDate has been declined.$reasonText";
    }

    // Get current datetime in Philippine time
    $philippineTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $createdAt = $philippineTime->format('Y-m-d H:i:s');

    // Insert notification with explicit Philippine time
    $stmt = $conn->prepare("
        INSERT INTO notifications_tb (user_id, booking_id, message, created_at)
        VALUES (:user_id, :booking_id, :message, :created_at)
    ");
    $stmt->bindParam(':user_id', $customer_id, PDO::PARAM_INT);
    $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->bindParam(':created_at', $createdAt, PDO::PARAM_STR);
    
    return $stmt->execute();
}
?>