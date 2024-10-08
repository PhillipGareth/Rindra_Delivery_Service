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
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            opacity: 0;
            animation: fadeIn 0.5s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .dashboard-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .dashboard-header:hover {
            background-color: #0056b3;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
            transition: width 0.3s;
        }

        .sidebar a {
            color: white;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            height: 200px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            color: white;
        }

        .card-driver-id {
            background-color: #007bff;
        }

        .card-email {
            background-color: #28a745;
        }

        .card-actions {
            background-color: #ffc107;
        }

        .card-delivery-history {
            background-color: #17a2b8;
        }

        .card-title {
            font-size: 2rem;
            font-weight: bold;
        }

        .card-text {
            font-size: 0.9rem;
        }

        .btn {
            border-radius: 20px;
            width: 150px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .content-area {
            margin-left: 250px;
            padding: 20px;
        }

        .card-group {
            display: flex;
            justify-content: space-between;
        }

        .card-group .card {
            flex: 1;
            margin: 0 10px;
        }

        .delivery-history-card {
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

        <div class="row delivery-history-card">
            <div class="col-md-4">
                <div class="card text-center card-delivery-history">
                    <div class="card-header">
                        Delivery History
                    </div>
                    <div class="card-body">
                        <h2 class="card-title">Delivery History</h2>
                        <p class="card-text">Check your past deliveries</p>
                        <a href="driver_delivery_history.php" class="btn btn-success">View History</a>
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
