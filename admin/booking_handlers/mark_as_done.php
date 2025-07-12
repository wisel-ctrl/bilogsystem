<?php
// mark_as_done.php

// Include database connection
require_once '../../db_connect.php';

// Get booking ID from POST request
$bookingId = $_POST['booking_id'];

try {
    // Start transaction
    $conn->beginTransaction();

    // 1. Update booking status to 'done'
    $updateBooking = $conn->prepare("UPDATE booking_tb SET booking_status = 'done' WHERE booking_id = ?");
    $updateBooking->execute([$bookingId]);

    // 2. Get booking details
    $getBooking = $conn->prepare("SELECT package_id, pax, totalPrice, reservation_datetime FROM booking_tb WHERE booking_id = ?");
    $getBooking->execute([$bookingId]);
    $booking = $getBooking->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        throw new Exception("Booking not found");
    }

    $package_id = $booking['package_id'];
    $pax = $booking['pax'];
    $totalPrice = $booking['totalPrice'];
    $reservation_datetime = $booking['reservation_datetime'];

    // 3. Insert into sales_tb
    $insertSales = $conn->prepare("INSERT INTO sales_tb (total_price, amount_paid, sales_type, created_at) VALUES (?, ?, 'booking', ?)");
    $insertSales->execute([$totalPrice, $totalPrice, $reservation_datetime]);
    $lastSalesId = $conn->lastInsertId();

    // 4. Get package dishes and insert into order_tb
    $getPackageDishes = $conn->prepare("SELECT dish_id, quantity FROM menu_package_dishes_tb WHERE package_id = ?");
    $getPackageDishes->execute([$package_id]);
    $packageDishes = $getPackageDishes->fetchAll(PDO::FETCH_ASSOC);

    foreach ($packageDishes as $dish) {
        // Get dish price
        $getDishPrice = $conn->prepare("SELECT price FROM dishes_tb WHERE dish_id = ?");
        $getDishPrice->execute([$dish['dish_id']]);
        $dishPrice = $getDishPrice->fetchColumn();

        // Calculate total quantity (quantity * pax)
        $totalQuantity = $dish['quantity'] * $pax;

        // Insert into order_tb
        $insertOrder = $conn->prepare("INSERT INTO order_tb (sales_id, dish_id, quantity, price) VALUES (?, ?, ?, ?)");
        $insertOrder->execute([$lastSalesId, $dish['dish_id'], $totalQuantity, $dishPrice]);
    }

    // Commit transaction
    $conn->commit();

    // Return success response
    echo json_encode(['success' => true, 'message' => 'Booking marked as completed successfully']);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>