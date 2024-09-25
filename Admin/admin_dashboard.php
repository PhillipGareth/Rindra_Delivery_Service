<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Public/login.php'); // Redirect to login if not an admin
    exit;
}

// Admin dashboard content
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Rindra Delivery Service</title>
    <link rel="stylesheet" href="../style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <div class="container">
        <h2>Welcome, Admin!</h2>
        <p>Here you can manage orders, view drivers, and oversee client activities.</p>
        <nav>
            <ul>
                <li><a href="admin_createorder.php">Create Order</a></li>
                <li><a href="admin_assignorder.php">Assign Order</a></li>
                <li><a href="admin_trackorder.php">Track Orders</a></li>
                <li><a href="admin_updateorder.php">Update Orders</a></li>
                <li><a href="display_allorders.php">View All Orders</a></li>
                <li><a href="display_allupdate.php">View All Updates</a></li>
            </ul>
        </nav>
        <a href="../Public/logout.php">Logout</a>
    </div>
</body>
</html>
