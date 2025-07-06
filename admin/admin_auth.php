<?php
// Set session name for admin
session_name('ADMIN_SESSION');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check for admin user type (usertype = 1)
if ($_SESSION['usertype'] != 1) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

// Prevent caching for authenticated pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>