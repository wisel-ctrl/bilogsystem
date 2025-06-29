<?php
session_start();
header('Content-Type: application/json');

// Include database connection
require_once('../../db_connect.php');

// Initialize response array
$response = ['status' => 'error', 'message' => 'An error occurred'];

try {
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get and validate input data
    $package_id = filter_input(INPUT_POST, 'package_id', FILTER_VALIDATE_INT);
    $pax = filter_input(INPUT_POST, 'numberOfPax', FILTER_VALIDATE_INT);
    $reservation_datetime = filter_input(INPUT_POST, 'reservationDate', FILTER_SANITIZE_STRING);
    $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);
    $customer_id = $_SESSION['user_id']; // You should replace this with actual customer ID from session or auth

    // Validate required fields
    if (!$package_id || !$pax || !$reservation_datetime) {
        throw new Exception('Missing required fields');
    }

    // Validate date format
    if (!DateTime::createFromFormat('Y-m-d\TH:i', $reservation_datetime)) {
        throw new Exception('Invalid date format');
    }

    // Handle file upload
    $downpayment_img = null;
    if (isset($_FILES['paymentProof']) && $_FILES['paymentProof']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../images/payment_proofs/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($fileInfo, $_FILES['paymentProof']['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($detectedType, $allowedTypes)) {
            throw new Exception('Only JPG, PNG, and GIF files are allowed');
        }

        // Generate unique filename
        $extension = pathinfo($_FILES['paymentProof']['name'], PATHINFO_EXTENSION);
        $filename = 'payment_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['paymentProof']['tmp_name'], $destination)) {
            $downpayment_img = $filename;
        } else {
            throw new Exception('Failed to upload payment proof');
        }
    } else {
        throw new Exception('Payment proof is required');
    }

    // Calculate total price (you should fetch package price from database)
    // For now, we'll use a placeholder - replace this with actual calculation
    $totalPrice = $pax * 100; // Assuming 100 is the price per pax

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO booking_tb 
        (package_id, pax, totalPrice, reservation_datetime, notes, downpayment_img, customer_id) 
        VALUES (:package_id, :pax, :totalPrice, :reservation_datetime, :notes, :downpayment_img, :customer_id)");

    $stmt->bindParam(':package_id', $package_id);
    $stmt->bindParam(':pax', $pax);
    $stmt->bindParam(':totalPrice', $totalPrice);
    $stmt->bindParam(':reservation_datetime', $reservation_datetime);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':downpayment_img', $downpayment_img);
    $stmt->bindParam(':customer_id', $customer_id);

    if ($stmt->execute()) {
        $response = [
            'status' => 'success',
            'message' => 'Reservation submitted successfully!',
            'booking_id' => $conn->lastInsertId()
        ];
    } else {
        throw new Exception('Failed to save reservation to database');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    // Log the error for debugging
    error_log('Reservation Error: ' . $e->getMessage());
}

// Return JSON response
echo json_encode($response);
?>