<?php
require_once '../db_connect.php';
require_once 'admin_auth.php';

// Set the timezone to Philippine Time
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

try {
    // Handle different actions
    $action = $_GET['action'] ?? ($_POST['action'] ?? '');

    if ($action === 'fetch') {
        // Fetch all GCash QR codes
        $stmt = $conn->query("SELECT * FROM gcash_qr ORDER BY created_at DESC");
        $qrCodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'qrCodes' => $qrCodes
        ]);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($action === 'delete') {
            // Delete GCash QR
            $id = $_POST['id'];
            
            // First get the image filename to delete it from server
            $stmt = $conn->prepare("SELECT qr_image FROM gcash_qr WHERE id = ?");
            $stmt->execute([$id]);
            $qr = $stmt->fetch();
            
            if ($qr) {
                $imagePath = "../images/gcash_qr/" . $qr['qr_image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            // Delete from database
            $stmt = $conn->prepare("DELETE FROM gcash_qr WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'GCash QR deleted successfully'
            ]);
            exit();
        } else {
            // Add new GCash QR
            $gcashNumber = $_POST['gcash_number'];
            
            // Validate GCash number
            if (!preg_match('/^09[0-9]{9}$/', $gcashNumber)) {
                throw new Exception('GCash number must be 11 digits starting with 09.');
            }
            
            // Handle file upload
            if (!isset($_FILES['qr_image']) || $_FILES['qr_image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Please upload a valid QR code image.');
            }
            
            $file = $_FILES['qr_image'];
            
            // Validate file
            if ($file['size'] > 2000000) { // 2MB
                throw new Exception('File is too large. Max 2MB allowed.');
            }
            
            $validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($file['tmp_name']);
            
            if (!in_array($fileType, $validTypes)) {
                throw new Exception('Only JPG, PNG, and GIF files are allowed.');
            }
            
            // Create directory if not exists
            $targetDir = "../images/gcash_qr/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            // Generate unique filename
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $fileExt;
            $targetFile = $targetDir . $fileName;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                // Insert into database with explicit Philippine time
                $currentDateTime = date('Y-m-d H:i:s');
                $stmt = $conn->prepare("INSERT INTO gcash_qr (qr_image, gcash_number, created_at) VALUES (?, ?, ?)");
                $stmt->execute([$fileName, $gcashNumber, $currentDateTime]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'GCash QR added successfully'
                ]);
                exit();
            } else {
                throw new Exception('Error uploading file. Please try again.');
            }
        }
    }
    
    throw new Exception('Invalid request');
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit();
}