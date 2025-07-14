<?php
   // Prevent any output before JSON
   ob_start();

   // Enable error reporting for debugging (disable in production)
   ini_set('display_errors', 0); // Suppress errors in output
   ini_set('display_startup_errors', 0);
   error_reporting(E_ALL);

   // Log errors to a file
   ini_set('log_errors', 1);
   ini_set('error_log', __DIR__ . '/error.log');

   try {
       // Include required files
       if (!file_exists('../../db_connect.php')) {
           throw new Exception('Required file db_connect.php not found');
       }
       require_once '../../db_connect.php';
       require_once '../customer_auth.php';

       // Check if session is started
       if (session_status() === PHP_SESSION_NONE) {
           session_start();
       }

       // Verify user session
       if (!isset($_SESSION['user_id'])) {
           throw new Exception('User not authenticated');
       }

       // Fetch bookings for the logged-in user using PDO
       $user_id = $_SESSION['user_id'];
       if (!isset($conn) || !$conn instanceof PDO) {
           throw new Exception('Invalid PDO database connection');
       }

       // Pagination settings
       $items_per_page = 5;
       $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
       $offset = ($current_page - 1) * $items_per_page;

       // Get total number of bookings for pagination
       $count_query = "SELECT COUNT(*) as total 
                       FROM booking_tb 
                       WHERE customer_id = :user_id 
                       AND booking_status NOT IN ('cancel', 'done')";
       $count_stmt = $conn->prepare($count_query);
       $count_stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
       $count_stmt->execute();
       $total_bookings = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
       $total_pages = ceil($total_bookings / $items_per_page);

       // Fetch bookings for current page
       $query = "SELECT booking_id, reservation_datetime, event, pax, booking_status, decline_reason 
                 FROM booking_tb 
                 WHERE customer_id = :user_id 
                 AND booking_status NOT IN ('cancel', 'done')
                 ORDER BY reservation_datetime ASC 
                 LIMIT :limit OFFSET :offset";
       $stmt = $conn->prepare($query);
       if (!$stmt) {
           throw new Exception('Query preparation failed: ' . implode(' | ', $conn->errorInfo()));
       }
       $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
       $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
       $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
       if (!$stmt->execute()) {
           throw new Exception('Query execution failed: ' . implode(' | ', $stmt->errorInfo()));
       }
       $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
       $stmt->closeCursor();

       // Clear output buffer
       ob_end_clean();

       // Return JSON response
       header('Content-Type: application/json; charset=utf-8');
       echo json_encode([
           'success' => true,
           'bookings' => $bookings,
           'current_page' => $current_page,
           'total_pages' => $total_pages
       ], JSON_THROW_ON_ERROR);
   } catch (Exception $e) {
       // Clear output buffer
       ob_end_clean();

       error_log('Error in fetch_bookings.php: ' . $e->getMessage());
       header('Content-Type: application/json; charset=utf-8', true, 500);
       echo json_encode([
           'success' => false,
           'message' => 'An error occurred: ' . $e->getMessage()
       ], JSON_THROW_ON_ERROR);
   }
   ?>