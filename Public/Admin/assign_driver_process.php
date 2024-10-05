<?php
// assign_driver_process.php

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?error=You must log in as an admin to access this page.");
    exit();
}

require_once '../../Configuration/Database.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database();
$conn = $db->getConnection();

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $driverId = $_POST['driver_id'];

    // Prepare and execute the assignment
    try {
        $stmt = $conn->prepare("UPDATE orders SET driver_id = :driver_id WHERE order_id = :order_id");
        $stmt->bindParam(':driver_id', $driverId);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();

        // Redirect back with success message
        header("Location: admin_assigndriver.php?success=Driver assigned successfully!");
    } catch (Exception $e) {
        // Handle error
        header("Location: admin_assigndriver.php?error=" . urlencode("Failed to assign driver: " . $e->getMessage()));
    }
} else {
    header("Location: admin_assigndriver.php");
}