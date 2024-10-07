<?php
session_start(); // Start the session

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header('Location: ../../index.php'); // Redirect to login if not authorized
    exit;
}

// Include the necessary files with the correct paths
require_once '../../Configuration/Database.php'; // Corrected path to Database.php
require_once '../../classes/Driver.php'; // Corrected path to Driver.php

use RINDRA_DELIVERY_SERVICE\Database\Database;

// Instantiate the Database class
$db = new Database();
$conn = $db->getConnection(); // Get the database connection

// Fetch driver details
$driver = new \RINDRA_DELIVERY_SERVICE\Driver\Driver($conn); // Pass the database connection

try {
    // Ensure the user_id is a valid integer
    $userId = (int) $_SESSION['user_id'];
    $driverDetails = $driver->getDriverById($userId); 

    // Handle form submission to update status
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $status = $_POST['status'];
        // Call a method to update the driver's status (implement this method in your Driver class)
        // $driver->updateStatus($userId, $status);
        echo "<p>Status updated successfully!</p>"; // Placeholder for success message
    }

    // Display the update status form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Status</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
            }
            .container {
                margin-top: 20px;
                padding: 20px;
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            }
            .btn-logout {
                background-color: #dc3545;
                color: white;
            }
            .btn-logout:hover {
                background-color: #c82333;
            }
            .btn-dashboard {
                background-color: #007bff;
                color: white;
            }
            .btn-dashboard:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <h2>Update Status</h2>
        <form method="POST">
            <div class="form-group">
                <label for="status">Select Status:</label>
                <select name="status" class="form-control" required>
                    <option value="available">Available</option>
                    <option value="busy">Busy</option>
                    <option value="offline">Offline</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
            <a href="../../Public/index.php" class="btn btn-logout float-right">Logout</a>
        </form>
        <a href="driver_dashboard.php" class="btn btn-dashboard mt-3">Back to Dashboard</a> <!-- Back to Dashboard link added -->
    </div>
    </body>
    </html>
    <?php
} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>"; // Display error messages
} finally {
    $conn = null; // Ensure the database connection is closed
}
?>
