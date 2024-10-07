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

// Use null coalescing operator to avoid undefined index notice
$username = htmlspecialchars($driverDetails['username'] ?? 'Guest');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
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
        .card {
            border-radius: 10px; /* Card border radius */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); /* Card shadow */
            margin-bottom: 20px; /* Space between cards */
            height: 200px; /* Fixed height for cards */
        }
        .card-header {
            color: white; /* Header text color */
        }
        .card-driver-id {
            background-color: #007bff; /* Background color for Driver ID */
        }
        .card-email {
            background-color: #28a745; /* Background color for Email */
        }
        .card-actions {
            background-color: #ffc107; /* Background color for Actions */
        }
        .card-title {
            font-size: 2rem; /* Larger font for titles */
            font-weight: bold; /* Bold font */
        }
        .card-text {
            font-size: 0.9rem; /* Smaller font for email text */
        }
        .btn {
            border-radius: 20px; /* Button border radius */
            width: 150px; /* Fixed button width */
        }
        .content-area {
            margin-left: 250px; /* Space for sidebar */
            padding: 20px;
        }
        .card-group {
            display: flex;
            justify-content: space-between; /* Space between cards */
        }
        .card-group .card {
            flex: 1; /* Equal card size */
            margin: 0 10px; /* Space between cards */
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar position-fixed">
    <h2 class="text-center text-white">Driver Menu</h2>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="driver_dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="driver_assigneddriver.php">View Assigned Drivers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="driver_updatestatus.php">Update Delivery Status</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../index.php?logout=true">Logout</a>
        </li>
    </ul>
</div>

<!-- Main content -->
<div class="content-area">
    <div class="dashboard-header">
        <h1>Welcome to Driver Dashboard</h1>
    </div>

    <div class="container">
        <div class="row card-group">
            <div class="col-md-4">
                <div class="card text-center card-driver-id">
                    <div class="card-header">
                        Driver ID
                    </div>
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($driverDetails['id']); ?></h2>
                        <p class="card-text">Your unique driver identifier</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center card-email">
                    <div class="card-header">
                        Email
                    </div>
                    <div class="card-body">
                        <h2 class="card-title"><?php echo htmlspecialchars($driverDetails['email']); ?></h2>
                        <p class="card-text">Your registered email address</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center card-actions">
                    <div class="card-header">
                        Actions
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <a href="driver_assigneddriver.php" class="btn btn-info mb-2">View Assigned Drivers</a>
                        <a href="driver_updatestatus.php" class="btn btn-warning">Update Delivery Status</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
