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

    // Fetch assigned drivers (you may need to implement this method)
    $assignedDrivers = []; // Replace this with the actual method to fetch assigned drivers

    // Display the dashboard
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Assigned Drivers</title>
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
        </style>
    </head>
    <body>

    <div class="container">
        <h1 class="text-center">Assigned Drivers</h1>
        <div class="text-center">
            <a href="driver_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            <button class="btn btn-logout" onclick="logout()">Logout</button>
        </div>

        <div class="mt-4">
            <?php if (!empty($assignedDrivers)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignedDrivers as $driver): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($driver['id']); ?></td>
                                <td><?php echo htmlspecialchars($driver['username']); ?></td>
                                <td><?php echo htmlspecialchars($driver['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No assigned drivers found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../../logout.php'; // Redirect to logout script
            }
        }
    </script>

    </body>
    </html>
    <?php
} catch (Exception $e) {
    // Output the error message for debugging
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>