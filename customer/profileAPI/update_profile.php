<?php
session_start();
require_once '../../db_connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Unauthorized access.';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];
$first_name = trim($_POST['first_name'] ?? '');
$middle_name = trim($_POST['middle_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$suffix = trim($_POST['suffix'] ?? '');
$username = trim($_POST['username'] ?? '');
$contact_number = trim($_POST['contact_number'] ?? '');

// Basic validation
if (empty($first_name) || empty($last_name) || empty($username) || empty($contact_number)) {
    $response['message'] = 'All required fields must be filled.';
    echo json_encode($response);
    exit;
}

try {
    // Check if username is unique (excluding current user)
    $query = "SELECT id FROM users_tb WHERE username = :username AND id != :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->fetch()) {
        $response['message'] = 'Username is already taken.';
        echo json_encode($response);
        exit;
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
        $response['success'] = true;
        $response['message'] = 'Profile updated successfully.';
    } else {
        $response['message'] = 'Failed to update profile.';
    }
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?>