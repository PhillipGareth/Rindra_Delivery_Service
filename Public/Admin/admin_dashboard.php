<?php
// admin_dashboard.php

session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?error=You must log in as an admin to access this page.");
    exit();
}

require_once '../../Configuration/Database.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database();
$conn = $db->getConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
        }
        .dashboard-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar {
            background-color: #343a40;
            height: 100vh;
            padding: 15px;
            position: fixed;
            width: 220px;
        }
        .sidebar h4 {
            color: white;
        }
        .sidebar a {
            color: #adb5bd;
            display: block;
            margin: 10px 0;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar a:hover {
            background-color: #007bff;
            color: white;
        }
        .content {
            margin-left: 240px;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .logout-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4>Admin Navigation</h4>
    <a href="admin_assigndriver.php">Assign Driver</a>
    <a href="admin_createorder.php">Create Order</a>
    <a href="admin_manageorder.php">Manage Order</a>
    <a href="admin_vieworder.php">View Orders</a>
    <a href="admin_delivery_history.php">Delivery History</a> <!-- New link for Delivery History -->
    <a href="../logout.php" class="btn btn-danger btn-block logout-btn">Logout</a>
</div>

<div class="content">
    <div class="dashboard-header">
        <h1>Welcome to Admin Dashboard</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-header">Create Order</div>
                    <div class="card-body">
                        <h5 class="card-title">Order Management</h5>
                        <p class="card-text">Create new orders efficiently.</p>
                        <a href="admin_createorder.php" class="btn btn-light">Create Order</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-header">Manage Order</div>
                    <div class="card-body">
                        <h5 class="card-title">Order Management</h5>
                        <p class="card-text">Manage existing orders easily.</p>
                        <a href="admin_manageorder.php" class="btn btn-light">Manage Order</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-header">View Orders</div>
                    <div class="card-body">
                        <h5 class="card-title">Order Overview</h5>
                        <p class="card-text">Review all current and past orders.</p>
                        <a href="admin_vieworder.php" class="btn btn-light">View Orders</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-header">Assign Driver</div>
                    <div class="card-body">
                        <h5 class="card-title">Driver Assignment</h5>
                        <p class="card-text">Assign drivers to manage orders.</p>
                        <a href="admin_assigndriver.php" class="btn btn-light">Assign Driver</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary">
                    <div class="card-header">Delivery History</div>
                    <div class="card-body">
                        <h5 class="card-title">Track Deliveries</h5>
                        <p class="card-text">View the history of all deliveries.</p>
                        <a href="admin_delivery_history.php" class="btn btn-light">View History</a>
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
