<?php
require_once '../../db_connect.php'; // Adjust path if needed

// Enable error logging for debugging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_log("update_profile.php started at " . date('Y-m-d H:i:s'));

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

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize and validate input
        $first_name = trim($_POST['first_name'] ?? '');
        $middle_name = trim($_POST['middle_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $suffix = trim($_POST['suffix'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $contact_number = trim($_POST['contact_number'] ?? '');

        // Log received data for debugging
        error_log("Received data: first_name=$first_name, middle_name=$middle_name, last_name=$last_name, suffix=$suffix, username=$username, contact_number=$contact_number");

        // Basic validation
        if (empty($first_name) || empty($last_name) || empty($username) || empty($contact_number)) {
            error_log("Validation failed: Missing required fields");
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
            exit();
        }

        // Validate phone number format (example: allow digits and optional +)
        if (!preg_match('/^\+?\d{10,15}$/', $contact_number)) {
            error_log("Validation failed: Invalid phone number format");
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid phone number format']);
            exit();
        }

        // Check if username is unique (excluding current user)
        $query = "SELECT id FROM users_tb WHERE username = :username AND id != :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetch()) {
            error_log("Validation failed: Username $username already exists");
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username is already taken']);
            exit();
        }

        // Update user data
        $query = "UPDATE users_tb 
                  SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name, 
                      suffix = :suffix, username = :username, contact_number = :contact_number 
                  WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':middle_name', $middle_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $stmt->bindParam(':suffix', $suffix, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':contact_number', $contact_number, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            error_log("Profile updated successfully for user_id: $user_id");
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            error_log("Failed to update profile for user_id: $user_id");
            echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    error_log("Invalid request method: {$_SERVER['REQUEST_METHOD']}");
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}

exit();
?>