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
    $price = filter_input(INPUT_POST, 'package_price', FILTER_VALIDATE_FLOAT);
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
    $totalPrice = $pax * $price; // Assuming price is per pax

    date_default_timezone_set('Asia/Manila');
    $booking_datetime = date('Y-m-d H:i:s');

    // Insert into database with booking_datetime
    $stmt = $conn->prepare("INSERT INTO booking_tb 
    (package_id, pax, totalPrice, reservation_datetime, notes, downpayment_img, customer_id, booking_datetime) 
    VALUES (:package_id, :pax, :totalPrice, :reservation_datetime, :notes, :downpayment_img, :customer_id, :booking_datetime)");

    $stmt->bindParam(':package_id', $package_id);
    $stmt->bindParam(':pax', $pax);
    $stmt->bindParam(':totalPrice', $totalPrice);
    $stmt->bindParam(':reservation_datetime', $reservation_datetime);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':downpayment_img', $downpayment_img);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->bindParam(':booking_datetime', $booking_datetime);

    if ($stmt->execute()) {
        $booking_id = $conn->lastInsertId();
        
        // Get customer contact number
        $stmt = $conn->prepare("SELECT contact_number FROM users_tb WHERE id = :customer_id");
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && !empty($user['contact_number'])) {
            // Send SMS notification via Semaphore API
            $api_key = '487b60aae3df89ca35dc3b4dd69e2518';
            $sendername = 'CaffeLilio';
            $contact_number = $user['contact_number'];
            
            // Format the reservation datetime for better readability
            $formatted_date = date('F j, Y h:i A', strtotime($reservation_datetime));
            
            // Prepare SMS message for customer
            $message = "Thank you for your reservation at Caffe Lilio!\nBooking ID: $booking_id\nDate: $formatted_date\nPax: $pax, Total: PHP $totalPrice\nWe'll confirm soon!";
            
            // Prepare API request for customer
            $ch = curl_init();
            $parameters = [
                'apikey' => $api_key,
                'number' => $contact_number,
                'message' => $message,
                'sendername' => $sendername
            ];
            
            curl_setopt($ch, CURLOPT_URL, 'https://api.semaphore.co/api/v4/messages');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $result = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code !== 200) {
                error_log('Customer SMS sending failed: ' . $result);
            }
        } else {
            error_log('No valid contact number found for customer ID: ' . $customer_id);
        }

        // Admin Notification: Fetch admins with usertype = 1
        $stmt = $conn->prepare("SELECT contact_number FROM users_tb WHERE usertype = 1");
        $stmt->execute();
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($admins) {
            // Prepare SMS message for admins
            $admin_message = "New booking at Caffe Lilio!\nBooking ID: $booking_id\nCustomer ID: $customer_id\nDate: $formatted_date\nPax: $pax\nTotal: PHP $totalPrice";

            // Send SMS to each admin
            foreach ($admins as $admin) {
                if (!empty($admin['contact_number'])) {
                    $ch = curl_init();
                    $parameters = [
                        'apikey' => $api_key,
                        'number' => $admin['contact_number'],
                        'message' => $admin_message,
                        'sendername' => $sendername
                    ];

                    curl_setopt($ch, CURLOPT_URL, 'https://api.semaphore.co/api/v4/messages');
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $result = curl_exec($ch);
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($http_code !== 200) {
                        error_log('Admin SMS sending failed for number ' . $admin['contact_number'] . ': ' . $result);
                    }
                } else {
                    error_log('No valid contact number found for admin with usertype = 1');
                }
            }
        } else {
            error_log('No admins found with usertype = 1');
        }

        $response = [
            'status' => 'success',
            'message' => 'Reservation submitted successfully!',
            'booking_id' => $booking_id
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