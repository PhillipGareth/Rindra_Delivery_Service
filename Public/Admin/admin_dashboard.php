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
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
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
    <div class="dashboard-header">
        <h1>Welcome to Admin Dashboard</h1>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Admin Navigation
                </div>
                <div class="card-body">
                    <a href="admin_assigndriver.php" class="btn btn-primary btn-block">Assign Driver</a>
                    <a href="admin_createorder.php" class="btn btn-secondary btn-block">Create Order</a>
                    <a href="admin_manageorder.php" class="btn btn-info btn-block">Manage Order</a>
                    <a href="admin_vieworder.php" class="btn btn-warning btn-block">View Orders</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Dashboard Overview
                </div>
                <div class="card-body">
                    <h5 class="card-title">Admin Functions</h5>
                    <p class="card-text">Manage drivers, create orders, and view all order processes from this dashboard.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <div class="text-center logout-btn">
        <a href="../logout.php" class="btn btn-danger btn-block">Logout</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>