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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all fields are set and not empty
    if (isset($_POST['client_id'], $_POST['address'], $_POST['contact_info'])) {
        $client_id = $_POST['client_id'];
        $address = $_POST['address'];
        $contact_info = $_POST['contact_info'];

        // SQL statement to insert the order
        $stmt = $conn->prepare("INSERT INTO orders (client_id, address, contact_info) VALUES (?, ?, ?)");
        if ($stmt->execute([$client_id, $address, $contact_info])) {
            header("Location: admin_vieworder.php?success=Order created successfully");
            exit();
        } else {
            $error = "Failed to create order";
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
</head>
<body>

<div class="container mt-5">
    <h1>Create Order</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="client_id">Client ID:</label>
            <input type="number" id="client_id" name="client_id" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="contact_info">Contact Information:</label>
            <input type="text" id="contact_info" name="contact_info" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Create Order</button>
    </form>
    <?php if ($error) { echo "<p class='text-danger'>$error</p>"; } ?>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>