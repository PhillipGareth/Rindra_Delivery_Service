<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?error=You must log in to access this page.");
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: ../index.php"); // Redirect to login page after logout
    exit();
}

// Include necessary files
require_once '../../Configuration/Database.php'; // Adjust the path if necessary
require_once '../../classes/Client.php'; // Adjust the path if necessary

use RINDRA_DELIVERY_SERVICE\Database\Database;
use RINDRA_DELIVERY_SERVICE\Client\Client;

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

// Fetch client information or orders if needed
$user_id = $_SESSION['user_id'];
$client = new Client($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 5px;
        }
        .card {
            margin-top: 20px;
        }
        .logout-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="dashboard-header text-center">
        <h1>Welcome to Client Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    User Navigation
                </div>
                <div class="card-body">
                    <a href="client_vieworder.php" class="btn btn-primary btn-block">View Your Orders</a>
                    <a href="client_profile.php" class="btn btn-secondary btn-block">Edit Profile</a>
                    <a href="client_support.php" class="btn btn-info btn-block">Support</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Dashboard Overview
                </div>
                <div class="card-body">
                    <h5 class="card-title">Your Recent Activity</h5>
                    <p class="card-text">Here you can view your recent orders and manage your profile settings.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <form method="post" action="" class="logout-btn">
        <button type="submit" name="logout" class="btn btn-danger btn-block">Logout</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>