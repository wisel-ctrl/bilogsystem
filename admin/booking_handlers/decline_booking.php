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
    // First, get the customer's phone number and name from the database
    $stmt = $conn->prepare("
        SELECT u.contact_number, CONCAT(u.first_name, ' ', u.last_name) AS customer_name, b.reservation_datetime 
        FROM booking_tb b
        JOIN users_tb u ON b.customer_id = u.id
        WHERE b.booking_id = :bookingId
    ");
    $stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
    $bookingDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bookingDetails) {
        throw new Exception('Booking details not found');
    }

    $phoneNumber = $bookingDetails['contact_number'];
    $customerName = $bookingDetails['customer_name'];
    $reservationDate = date('F j, Y h:i A', strtotime($bookingDetails['reservation_datetime']));

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
        // Send SMS notification
        $message = "Hello $customerName, we regret to inform you that your booking for $reservationDate has been declined. Reason: $reason";
        sendSMS($phoneNumber, $message);

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
} catch (Exception $e) {
    // Other errors
    header('HTTP/1.1 400 Bad Request');
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
?>