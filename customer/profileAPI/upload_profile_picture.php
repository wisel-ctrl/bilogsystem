<?php
require_once '../../db_connect.php';
require_once '../customer_auth.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if file was uploaded
if (!isset($_FILES['profile_picture'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

$file = $_FILES['profile_picture'];

// Validate file
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxSize = 2 * 1024 * 1024; // 2MB

if (!in_array($file['type'], $allowedTypes)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WEBP are allowed.']);
    exit;
}

if ($file['size'] > $maxSize) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 2MB.']);
    exit;
}

// Create directory if it doesn't exist
$uploadDir = '../../images/profile_pictures/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate unique filename
$fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'user_' . $user_id . '_' . uniqid() . '.' . $fileExt;
$destination = $uploadDir . $filename;

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file']);
    exit;
}

// Update database with new profile picture
try {
    // First, get old picture to delete it
    $query = "SELECT profile_picture FROM users_tb WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Delete old picture if it exists and isn't the default avatar
    if ($result && $result['profile_picture'] && strpos($result['profile_picture'], 'http') === false) {
        $oldFile = $uploadDir . $result['profile_picture'];
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
    }
    
    // Update with new picture
    $query = "UPDATE users_tb SET profile_picture = :filename WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':filename', $filename, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Profile picture updated successfully',
        'new_image_path' => $uploadDir . $filename
    ]);
} catch (PDOException $e) {
    // Delete the uploaded file if database update failed
    if (file_exists($destination)) {
        unlink($destination);
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>