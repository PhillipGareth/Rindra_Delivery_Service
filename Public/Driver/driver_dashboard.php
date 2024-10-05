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
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .card {
            margin: 20px 0;
        }
    </style>
</head>
<body>

<div class="dashboard-header">
    <h1>Welcome, <?php echo htmlspecialchars($driverDetails['username']); ?>!</h1>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
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
            <div class="card text-center">
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
            <div class="card text-center">
                <div class="card-header">
                    Actions
                </div>
                <div class="card-body">
                    <a href="driver_assigneddriver.php" class="btn btn-info mb-2">View Assigned Drivers</a>
                    <a href="driver_updatestatus.php" class="btn btn-warning">Update Delivery Status</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="../index.php?logout=true" class="btn btn-secondary">Logout</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>