<?php
require_once '../db_connect.php';
require_once 'customer_auth.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("
        SELECT 
            n.notification_id,
            n.message,
            n.is_read,
            n.created_at,
            TIMESTAMPDIFF(SECOND, n.created_at, NOW()) as time_diff
        FROM notifications_tb n
        WHERE n.user_id = :user_id
        ORDER BY n.created_at DESC
        LIMIT 10
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format time ago
    foreach ($notifications as &$notification) {
        $seconds = $notification['time_diff'];
        if ($seconds < 60) {
            $notification['time_ago'] = 'Just now';
        } elseif ($seconds < 3600) {
            $mins = floor($seconds / 60);
            $notification['time_ago'] = $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($seconds < 86400) {
            $hours = floor($seconds / 3600);
            $notification['time_ago'] = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } else {
            $days = floor($seconds / 86400);
            $notification['time_ago'] = $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        }
    }

    header('Content-Type: application/json');
    echo json_encode($notifications);
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}