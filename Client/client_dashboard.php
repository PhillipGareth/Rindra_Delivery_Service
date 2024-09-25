<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: ../Public/login.php'); // Redirect to login if not a client
    exit;
}

// Client dashboard content
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - Rindra Delivery Service</title>
    <link rel="stylesheet" href="../style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Client Dashboard</h1>
    </header>
    <div class="container">
        <h2>Welcome, Client!</h2>
        <p>Here you can manage your orders and view your order history.</p>
        <nav>
            <ul>
                <li><a href="view_orders.php">View Orders</a></li>
                <li><a href="create_order.php">Create New Order</a></li>
            </ul>
        </nav>
        <a href="../Public/logout.php">Logout</a>
    </div>
</body>
</html>
