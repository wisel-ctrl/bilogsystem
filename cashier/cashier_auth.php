<?php
// Set secure session cookie parameters
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true, // Requires HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Set session name for cashier
session_name('CASHIER_SESSION');
session_start();

// Check if user is logged in and has cashier user type
if (!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 2) {
    error_log("Cashier auth failed: user_id=" . ($_SESSION['user_id'] ?? 'not set') . ", usertype=" . ($_SESSION['usertype'] ?? 'not set'));
    session_destroy();
    header("Location: ../login.php");
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>