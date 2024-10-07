<?php
// admin_createorder.php

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?error=You must log in as an admin to access this page.");
    exit();
}

require_once '../../Configuration/Database.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database();
$conn = $db->getConnection();

$error = '';  // Initialize error variable
$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all fields are set and not empty
    if (isset($_POST['client_id'], $_POST['client_name'], $_POST['driver_name'], $_POST['address'], $_POST['contact_info'])) {
        $client_id = trim($_POST['client_id']);
        $client_name = trim($_POST['client_name']);
        $driver_name = trim($_POST['driver_name']);
        $address = trim($_POST['address']);
        $contact_info = trim($_POST['contact_info']);

        // Validate input
        if (empty($client_id) || empty($client_name) || empty($driver_name) || empty($address) || empty($contact_info)) {
            $error = "All fields are required.";
        } else {
            // Set default value for driver_id (if applicable)
            $driver_id = null; // Adjust if you have a way to select a driver ID

            // SQL statement to insert the order
            $stmt = $conn->prepare("INSERT INTO orders (client_id, client_name, address, contact_info, driver_id, driver_name) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$client_id, $client_name, $address, $contact_info, $driver_id, $driver_name])) {
                $message = "Order created successfully.";
                // Optionally redirect to view orders page
                header("Location: admin_vieworder.php?success=" . urlencode($message));
                exit();
            } else {
                $error = "Failed to create order.";
            }
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Create Order</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php elseif ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <form action="" method="post">
        <div class="form-group">
            <label for="client_id">Client ID:</label>
            <input type="number" id="client_id" name="client_id" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="client_name">Client Name:</label>
            <input type="text" id="client_name" name="client_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="driver_name">Driver Name:</label>
            <input type="text" id="driver_name" name="driver_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="contact_info">Contact Information:</label>
            <input type="text" id="contact_info" name="contact_info" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success btn-block">Create Order</button>
    </form>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3 btn-block">Back to Dashboard</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>