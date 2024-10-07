<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login page if not logged in
    exit();
}

// Include the Database class using the correct namespace
require_once '../../Configuration/Database.php'; 

use RINDRA_DELIVERY_SERVICE\Database\Database; // Use the Database class from the defined namespace

// Create a Database object
$db = new Database();
$conn = $db->getConnection(); // Get the PDO connection

// Fetch orders from the database
$query = "SELECT o.order_id, c.client_name, d.driver_name, o.status 
          FROM orders o 
          LEFT JOIN clients c ON o.client_id = c.id 
          LEFT JOIN drivers d ON o.driver_id = d.id";

$orders = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Added margin to create space between header and table */
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .back-button {
            display: inline-block;
            margin: 20px 0; /* Adjusted margin for spacing */
            padding: 10px 15px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>View Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Client Name</th>
                <th>Driver Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['client_name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($order['driver_name'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <a class="back-button" href="admin_dashboard.php">Back to Dashboard</a> <!-- Back to Dashboard link moved here -->
</div>

</body>
</html>
