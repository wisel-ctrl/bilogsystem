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