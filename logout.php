<?php
// Determine which session to destroy based on the 'usertype' parameter
$usertype = $_GET['usertype'] ?? '';

$session_name = '';
switch ($usertype) {
    case 'admin':
        $session_name = 'ADMIN_SESSION';
        break;
    case 'cashier':
        $session_name = 'CASHIER_SESSION';
        break;
    case 'customer':
        $session_name = 'CUSTOMER_SESSION';
        break;
    default:
        // Invalid usertype, redirect to login
        header("Location: login.php");
        exit();
}

// Set the session name
session_name($session_name);
session_start();

// Unset all session variables
$_SESSION = array();

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        $session_name,
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>