<?php
require_once 'customer_auth.php';
require_once '../db_connect.php';

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("
        SELECT 
            notification_id,
            message,
            is_read,
            created_at,
            TIMESTAMPDIFF(SECOND, created_at, NOW()) as seconds_ago
        FROM notifications_tb 
        WHERE user_id = :user_id
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the time ago
    foreach ($notifications as &$notification) {
        $seconds = $notification['seconds_ago'];
        if ($seconds < 60) {
            $notification['time_ago'] = 'Just now';
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $notification['time_ago'] = $minutes . ' min' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($seconds < 86400) {
            $hours = floor($seconds / 3600);
            $notification['time_ago'] = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } else {
            $days = floor($seconds / 86400);
            $notification['time_ago'] = $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        }
    }
    
    echo json_encode($notifications);
} catch (PDOException $e) {
    error_log("Error fetching notifications: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}