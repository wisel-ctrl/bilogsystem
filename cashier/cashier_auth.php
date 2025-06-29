<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check for cashier user type (usertype = 2)
if ($_SESSION['usertype'] != 2) {
    // Redirect to appropriate page based on user type
    switch ($_SESSION['usertype']) {
        case 1:
            header("Location: ../admin/admin_dashboard.php");
            break;
        case 3:
            header("Location: ../customer/customerindex.php");
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