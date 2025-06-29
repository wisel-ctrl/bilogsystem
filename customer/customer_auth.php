<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check for customer user type (usertype = 3)
if ($_SESSION['usertype'] != 3) {
    // Redirect to appropriate page based on user type
    switch ($_SESSION['usertype']) {
        case 1:
            header("Location: ../admin/admin_dashboard.php");
            break;
        case 2:
            header("Location: ../cashier/cashierindex.php");
            break;
        default:
            // Invalid usertype - destroy session and redirect to login
            session_destroy();
            header("Location: ../login.php");
    }
    exit();
}

// Prevent caching for authenticated pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>