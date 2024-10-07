<?php
namespace RINDRA_DELIVERY_SERVICE\Public\Admin;

// Adjusting the path based on your directory structure
require_once '../../Configuration/Database.php'; // Correct path to Database.php

use RINDRA_DELIVERY_SERVICE\Database\Database;

session_start();

$db = new Database();
$pdo = $db->getConnection();

// Initialize variables
$client_id = '';
$address = '';
$contact_info = '';
$status = 'Pending'; // Default status
$client_name = '';
$driver_id = NULL; // Assuming you want to set this later

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_POST['client_id'];
    $address = $_POST['address'];
    $contact_info = $_POST['contact_info'];
    $client_name = $_POST['client_name']; // Assuming this is passed from the form

    // Validate if the client_id exists in clients table
    $checkClientQuery = "SELECT id FROM clients WHERE id = :client_id";
    $stmt = $pdo->prepare($checkClientQuery);
    $stmt->execute([':client_id' => $client_id]);
    
    if ($stmt->rowCount() > 0) {
        // If client exists, proceed to insert order
        try {
            $insertOrderQuery = "INSERT INTO orders (client_id, address, contact_info, driver_id, status, client_name) 
                                 VALUES (:client_id, :address, :contact_info, :driver_id, :status, :client_name)";
            $stmt = $pdo->prepare($insertOrderQuery);
            $stmt->execute([
                ':client_id' => $client_id,
                ':address' => $address,
                ':contact_info' => $contact_info,
                ':driver_id' => $driver_id,
                ':status' => $status,
                ':client_name' => $client_name
            ]);
            echo "<div class='alert alert-success'>Order created successfully!</div>";
        } catch (\PDOException $e) {
            // Handle any errors during the insert
            echo "<div class='alert alert-danger'>Error creating order: " . $e->getMessage() . "</div>";
        }
    } else {
        // Handle the case where the client does not exist
        echo "<div class='alert alert-danger'>Error: Client with ID $client_id does not exist.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .form-control, .btn {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Create Order</h2>
        <form action="" method="POST">
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="client_id">Client ID:</label>
                    <input type="number" name="client_id" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="client_name">Client Name:</label>
                    <input type="text" name="client_name" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contact_info">Contact Info:</label>
                <input type="text" name="contact_info" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Create Order</button>
        </form>
        <a href="admin_dashboard.php" class="btn btn-secondary btn-block">Return to Dashboard</a> <!-- Return to Dashboard Button -->
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
