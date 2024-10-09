<?php
namespace RINDRA_DELIVERY_SERVICE\Public\Driver;

// Start the session only if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the Database.php file exists before including
if (!file_exists(__DIR__ . '/../../Configuration/Database.php')) {
    die("Database.php file not found!");
}

// Include necessary files from the correct path
require_once __DIR__ . '/../../Configuration/Database.php'; // Path to Database.php
require_once __DIR__ . '/../../classes/Driver.php'; // Path to Driver class

use RINDRA_DELIVERY_SERVICE\Database\Database;
use RINDRA_DELIVERY_SERVICE\Driver\Driver;

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header('Location: ../index.php'); // Redirect to login if not logged in
    exit();
}

// Create Driver object
$driver = new Driver($conn);

// Get driver details
$driverId = $_SESSION['user_id'];
$driverDetails = $driver->getDriverById($driverId); // Retrieve driver details

if (!$driverDetails) {
    die("Driver not found."); // Handle case where driver does not exist
}

// Fetch delivery history
$deliveryHistory = $driver->getDeliveryHistory($driverId); // Assume this method exists

if (!$deliveryHistory) {
    $deliveryHistory = []; // Default to empty array if no records found
}

// Use null coalescing operator to avoid undefined index notice
$username = htmlspecialchars($driverDetails['driver_name'] ?? 'Guest');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Delivery History</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light background */
            font-family: Arial, sans-serif;
        }
        .dashboard-header {
            background-color: #007bff; /* Header color */
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .sidebar {
            height: 100vh; /* Full height sidebar */
            background-color: #343a40; /* Dark background for sidebar */
            padding-top: 20px;
        }
        .sidebar a {
            color: white; /* Sidebar link color */
        }
        .sidebar a:hover {
            background-color: #495057; /* Hover effect for sidebar links */
        }
        .content-area {
            margin-left: 250px; /* Space for sidebar */
            padding: 20px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar position-fixed">
    <h2 class="text-center text-white">Driver Menu</h2>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="driver_dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="driver_assigneddriver.php">View Assigned Drivers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="driver_updatestatus.php">Update Delivery Status</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="driver_delivery_history.php">Delivery History</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../index.php?logout=true">Logout</a>
        </li>
    </ul>
</div>

<!-- Main content -->
<div class="content-area">
    <div class="dashboard-header">
        <h1>Delivery History</h1>
        <p>Welcome, <?php echo $username; ?>!</p>
    </div>

    <div class="container">
        <?php if (empty($deliveryHistory)): ?>
            <div class="alert alert-warning" role="alert">
                No delivery history found.
            </div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Delivery Status</th>
                        <th>Delivery Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deliveryHistory as $history): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($history['order_id'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($history['status'] ?? 'Unknown'); ?></td>
                            <td><?php echo htmlspecialchars($history['delivery_date'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <a href="driver_dashboard.php" class="btn btn-primary">Return to Dashboard</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
