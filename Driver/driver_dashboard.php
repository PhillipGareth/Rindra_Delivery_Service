<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'driver') {
    header('Location: ../Public/login.php'); // Redirect to login if not a driver
    exit;
}

// Driver dashboard content
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard - Rindra Delivery Service</title>
    <link rel="stylesheet" href="../style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Driver Dashboard</h1>
    </header>
    <div class="container">
        <h2>Welcome, Driver!</h2>
        <p>Here you can view your assigned orders and update order statuses.</p>
        <nav>
            <ul>
                <li><a href="view_assigned_orders.php">View Assigned Orders</a></li>
                <li><a href="update_order_status.php">Update Order Status</a></li>
            </ul>
        </nav>
        <a href="../Public/logout.php">Logout</a>
    </div>
</body>
</html>
