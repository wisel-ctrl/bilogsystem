<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_log("Logout script started at " . date('Y-m-d H:i:s'));

// Get the usertype from the URL parameter
$usertype = $_GET['usertype'] ?? '';
error_log("Usertype received: $usertype");

// Map usertype to session name
$session_name = '';
switch (strtolower($usertype)) {
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
        error_log("Invalid usertype: $usertype");
        header("Location: login.php?error=invalid_usertype");
        exit();
}

// Set secure session cookie parameters
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true, // Requires HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Set the session name and start the session
session_name($session_name);
if (session_start()) {
    error_log("Session started successfully for $session_name");
} else {
    error_log("Failed to start session for $session_name");
}

// Log session data before destruction
error_log("Session data before destruction: " . json_encode($_SESSION));

// Unset all session variables
$_SESSION = [];

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    $cookie_set = setcookie(
        $session_name,
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
    if ($cookie_set) {
        error_log("Session cookie deleted for $session_name");
    } else {
        error_log("Failed to delete session cookie for $session_name");
    }
}

// Destroy the session
if (session_destroy()) {
    error_log("Session destroyed for $session_name");
} else {
    error_log("Failed to destroy session for $session_name");
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login page with success message
header("Location: login.php?logout=success&usertype=$usertype");
exit();
?>