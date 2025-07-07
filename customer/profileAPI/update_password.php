<?php
require_once '../../db_connect.php'; // Adjust path if needed

// Enable error logging for debugging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_log("update_password.php started at " . date('Y-m-d H:i:s'));

// Set secure session cookie parameters
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true, // Requires HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Set session name for customer
session_name('CUSTOMER_SESSION');
session_start();

// Check if user is authenticated and is a customer (usertype 3)
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 3) {
    error_log("Unauthorized access: user_id=" . ($_SESSION['user_id'] ?? 'not set') . ", usertype=" . ($_SESSION['usertype'] ?? 'not set'));
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized user']);
    exit();
}

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

try {
    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        error_log("Validation failed: Missing required fields");
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }

    if ($new_password !== $confirm_password) {
        error_log("Validation failed: New passwords do not match");
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'New passwords do not match']);
        exit();
    }

    // Validate password strength (e.g., minimum 8 characters with at least one number)
    if (strlen($new_password) < 8 || !preg_match('/\d/', $new_password)) {
        error_log("Validation failed: New password does not meet requirements");
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'New password must be at least 8 characters long and contain at least one number']);
        exit();
    }

    // Verify current password
    $query = "SELECT password FROM users_tb WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($current_password, $user['password'])) {
        error_log("Validation failed: Current password is incorrect for user_id: $user_id");
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
        exit();
    }

    // Update password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users_tb SET password = :password WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        error_log("Password updated successfully for user_id: $user_id");
        echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
    } else {
        error_log("Failed to update password for user_id: $user_id");
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update password']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

exit();
?>