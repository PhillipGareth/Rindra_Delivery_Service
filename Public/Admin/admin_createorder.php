<?php
// admin_createorder.php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $address = $_POST['address'];
    $contact_info = $_POST['contact_info'];
    $client_id = $_POST['client_id'];

    // Fetch client name based on the selected ID
    $clientStmt = $conn->prepare("SELECT client_name FROM clients WHERE id = ?");
    $clientStmt->execute([$client_id]);
    $client = $clientStmt->fetch(PDO::FETCH_ASSOC);
    $client_name = $client ? $client['client_name'] : null;

    // Check if client name was found
    if ($client_name) {
        // Prepare and execute the insert statement without driver_id
        $stmt = $conn->prepare("INSERT INTO orders (client_id, address, contact_info, client_name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$client_id, $address, $contact_info, $client_name]);

        if ($stmt) {
            echo "<script>alert('Order created successfully!'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Failed to create order. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Invalid client selection.');</script>";
    }
}

// Fetch all clients
$clientStmt = $conn->prepare("SELECT id, client_name FROM clients");
$clientStmt->execute();
$clients = $clientStmt->fetchAll(PDO::FETCH_ASSOC);
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
            background-color: #e9ecef;
        }
        .form-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container form-container">
    <h2>Create Order</h2>
    <form method="POST" action="admin_createorder.php">
        <div class="form-group">
            <label for="client_id">Select Client</label>
            <select name="client_id" id="client_id" class="form-control" required>
                <option value="">Select Client</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id']; ?>"><?= htmlspecialchars($client['client_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" name="address" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="contact_info">Contact Info</label>
            <input type="text" name="contact_info" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Order</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Return to Dashboard</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
