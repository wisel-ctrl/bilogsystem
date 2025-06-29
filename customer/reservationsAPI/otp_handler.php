<?php
session_start();
require_once '../customer_auth.php';
require_once '../../db_connect.php';

$api_key = '487b60aae3df89ca35dc3b4dd69e2518';
$sender_name = 'CaffeLilio';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'generate_otp') {
            // Get user phone number from database
            $user_id = $_SESSION['user_id'] ?? null;
            if (!$user_id) {
                throw new Exception('User not logged in');
            }

            $stmt = $conn->prepare("SELECT contact_number FROM users_tb WHERE id = :user_id");
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user || empty($user['contact_number'])) {
                throw new Exception('We couldn\'t find your contact number. Please update your profile with a valid phone number.');
            }


            // Generate 6-digit OTP
            $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store OTP in session with timestamp
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_timestamp'] = time();
            $_SESSION['otp_booking_id'] = $_POST['booking_id'];

            // Send OTP via SMS (using your SMS API)
            $phone_number = preg_replace('/[^0-9]/', '', $user['contact_number']);
            if (substr($phone_number, 0, 1) === '0') {
                $phone_number = '63' . substr($phone_number, 1); // Convert to international format for PH
            }
            $message = "Your Caffe Lilio OTP for reservation cancellation is: $otp. Valid for 5 minutes.";
            
            $sms_data = [
                'apikey' => $api_key,
                'number' => $phone_number,
                'message' => $message,
                'sendername' => $sender_name
            ];
            
            $ch = curl_init('https://api.semaphore.co/api/v4/messages');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($sms_data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            // Log the response for debugging
            error_log("SMS API Response: " . $response);
            
            // Check if response is valid JSON
            $response_data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("Invalid JSON response from SMS API: " . $response);
                throw new Exception('Failed to send OTP via SMS: Invalid API response');
            }
            
            // Check for success in response
            if ($http_code === 200) {
                // Semaphore API v4 returns an array of message objects
                if (isset($response_data[0]['status']) && $response_data[0]['status'] === 'Pending') {
                    echo json_encode(['success' => true, 'message' => 'OTP sent successfully']);
                } else {
                    error_log("SMS API Error: " . print_r($response_data, true));
                    throw new Exception('Failed to send OTP via SMS: ' . ($response_data[0]['message'] ?? 'Unknown error'));
                }
            } else {
                error_log("SMS API HTTP Error: $http_code - " . print_r($response_data, true));
                throw new Exception('Failed to send OTP via SMS: HTTP ' . $http_code);
            }
        }
        elseif ($action === 'verify_otp') {
            // Validate input
            if (!isset($_POST['otp']) || empty(trim($_POST['otp']))) {
                throw new Exception('Please enter the OTP code');
            }
            
            $input_otp = trim($_POST['otp']);
            $booking_id = $_SESSION['otp_booking_id'] ?? null;

            // Check if OTP exists and is valid (within 5 minutes)
            if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_timestamp'])) {
                throw new Exception('No OTP session found. Please request a new OTP.');
            }

            $otp_expiry = 5 * 60; // 5 minutes in seconds
            if (time() - $_SESSION['otp_timestamp'] > $otp_expiry) {
                unset($_SESSION['otp'], $_SESSION['otp_timestamp'], $_SESSION['otp_booking_id']);
                throw new Exception('OTP expired. Please request a new OTP.');
            }

            if ($input_otp !== $_SESSION['otp']) {
                throw new Exception('The OTP you entered is incorrect. Please try again.');
            }

            // OTP is valid, proceed with cancellation
            $stmt = $conn->prepare("UPDATE booking_tb SET booking_status = 'declined' WHERE booking_id = :booking_id AND customer_id = :user_id");
            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $result = $stmt->execute();

            if ($result && $stmt->rowCount() > 0) {
                // Clear OTP session
                unset($_SESSION['otp'], $_SESSION['otp_timestamp'], $_SESSION['otp_booking_id']);
                echo json_encode(['success' => true, 'message' => 'Reservation cancelled successfully']);
            } else {
                throw new Exception('Failed to cancel reservation. No changes were made.');
            }
        }
        else {
            throw new Exception('Invalid action');
        }
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>