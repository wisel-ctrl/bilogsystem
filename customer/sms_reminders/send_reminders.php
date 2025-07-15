<?php
// send_reminders.php
require_once '../../db_connect.php';
require_once 'sms_helper.php';

// Get today's date
$today = date('Y-m-d');

try {
    // Query to get all accepted reservations within the next 3 days
    $stmt = $conn->prepare("
        SELECT b.booking_id, b.reservation_datetime, b.customer_id, 
               u.contact_number, u.first_name, u.last_name,
               DATEDIFF(DATE(b.reservation_datetime), :today) AS days_remaining
        FROM booking_tb b
        JOIN users_tb u ON b.customer_id = u.id
        WHERE b.booking_status = 'accepted'
        AND DATE(b.reservation_datetime) BETWEEN DATE_ADD(:today, INTERVAL 1 DAY) 
                                        AND DATE_ADD(:today, INTERVAL 3 DAY)
        AND (b.last_reminder_sent IS NULL OR DATE(b.last_reminder_sent) != :today)
    ");
    
    $stmt->bindParam(':today', $today);
    $stmt->execute();
    
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($reservations as $reservation) {
        $daysRemaining = $reservation['days_remaining'];
        
        // Only send reminders for 3, 2, and 1 day before
        if ($daysRemaining >= 1 && $daysRemaining <= 3) {
            $reservationDate = date('F j, Y', strtotime($reservation['reservation_datetime']));
            $reservationTime = date('h:i A', strtotime($reservation['reservation_datetime']));
            
            $message = "Hello {$reservation['first_name']},\n\n";
            $message .= "Reminder: Your reservation at Caffe Lilio is in {$daysRemaining} day" . ($daysRemaining > 1 ? 's' : '') . ".\n";
            $message .= "Date: {$reservationDate}\n";
            $message .= "Time: {$reservationTime}\n\n";
            
            if ($daysRemaining == 1) {
                $message .= "We're excited to see you tomorrow!";
            } else {
                $message .= "We look forward to serving you!";
            }
            
            // Send SMS
            $response = sendSMS($reservation['contact_number'], $message);
            
            // Update the booking record to mark that reminder was sent today
            $updateStmt = $conn->prepare("
                UPDATE booking_tb 
                SET last_reminder_sent = NOW() 
                WHERE booking_id = :booking_id
            ");
            $updateStmt->bindParam(':booking_id', $reservation['booking_id']);
            $updateStmt->execute();
            
            // Log the SMS sending
            file_put_contents('sms_log.txt', 
                "[" . date('Y-m-d H:i:s') . "] Sent to {$reservation['contact_number']}: " . 
                "{$daysRemaining}-day reminder\nResponse: {$response}\n\n", 
                FILE_APPEND);
        }
    }
    
    // Output for scheduled task logging
    echo count($reservations) . " reminders processed successfully at " . date('Y-m-d H:i:s') . "\n";
    
} catch(PDOException $e) {
    $errorMessage = "[" . date('Y-m-d H:i:s') . "] Error: " . $e->getMessage() . "\n";
    file_put_contents('error_log.txt', $errorMessage, FILE_APPEND);
    echo $errorMessage;
}
?>