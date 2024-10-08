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
            background-color: #f3f4f6; /* Light grey background */
            font-family: Arial, sans-serif;
        }
        .sidebar {
            height: 100vh; /* Full height */
            background-color: #2c3e50; /* Dark blue-gray */
            padding: 20px;
            position: fixed; /* Fixed sidebar */
            transition: all 0.3s ease;
        }
        .sidebar h2 {
            color: #ffffff;
            margin-bottom: 30px;
            font-size: 24px;
        }
        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 5px 0;
            transition: background-color 0.3s, transform 0.3s; /* Added transform transition */
        }
        .sidebar a:hover {
            background-color: #34495e; /* Slightly lighter on hover */
            transform: scale(1.05); /* Slightly grow on hover */
        }
        .main-content {
            margin-left: 250px; /* Space for the sidebar */
            padding: 20px; /* Padding for content */
        }
        .dashboard-header {
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card {
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            opacity: 0; /* Start invisible for animation */
            transform: translateY(20px); /* Start slightly lower for animation */
            transition: opacity 0.5s ease, transform 0.5s ease; /* Smooth transition */
        }
        .card.visible {
            opacity: 1; /* Fully visible */
            transform: translateY(0); /* Back to original position */
        }
        .btn-danger {
            background-color: #e74c3c; /* Red color for logout */
            border: none;
        }
        .btn-danger:hover {
            background-color: #c0392b; /* Darker red on hover */
        }
        .feature-box {
            margin-top: 20px;
            padding: 20px;
            color: #ffffff; /* White text */
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: background-color 0.3s, transform 0.3s; /* Added transform transition */
            opacity: 0; /* Start invisible for animation */
            transform: translateY(20px); /* Start slightly lower for animation */
        }
        .feature-box-1 { background-color: #28a745; } /* Green */
        .feature-box-2 { background-color: #007bff; } /* Blue */
        .feature-box-3 { background-color: #ffc107; } /* Yellow */
        .feature-box-4 { background-color: #17a2b8; } /* Teal for Delivery History */
        .feature-box.visible {
            opacity: 1; /* Fully visible */
            transform: translateY(0); /* Back to original position */
        }
        .feature-box:hover {
            transform: scale(1.05); /* Slightly grow on hover */
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Client Dashboard</h2>
    <a href="client_vieworder.php">View Your Orders</a>
    <a href="client_delivery_history.php">Delivery History</a>
    <form method="post" action="" class="logout-btn">
        <button type="submit" name="logout" class="btn btn-danger btn-block">Logout</button>
    </form>
</div>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Welcome to Client Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="feature-box feature-box-1">
                <h4>View Your Orders</h4>
                <a href="client_vieworder.php" class="btn btn-light btn-block">Go to Orders</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="feature-box feature-box-4">
                <h4>Delivery History</h4>
                <a href="client_delivery_history.php" class="btn btn-light btn-block">View History</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
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
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Animate feature boxes
        $('.feature-box').each(function(index) {
            $(this).delay(index * 200).queue(function() {
                $(this).addClass('visible');
                $(this).dequeue();
            });
        });

        // Animate card
        $('.card').delay($('.feature-box').length * 200).queue(function() {
            $(this).addClass('visible');
            $(this).dequeue();
        });
    });
</script>
</body>
</html>
