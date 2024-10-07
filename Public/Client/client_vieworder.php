<?php
// client_vieworder.php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?error=You must log in to access this page.");
    exit();
}

require_once '../../Configuration/Database.php'; // Adjust the path if necessary

use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database();
$conn = $db->getConnection();

// Fetching orders for the logged-in user
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE client_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Your Orders</h1>

    <?php if (count($orders) === 0): ?>
        <div class="alert alert-info">No orders found.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Address</th>
                    <th>Contact Info</th>
                    <th>Status</th>
                    <th>Date Ordered</th> <!-- Added Date Ordered Column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['address']); ?></td>
                        <td><?php echo htmlspecialchars($order['contact_info']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><?php echo htmlspecialchars($order['date_ordered'] ?? 'N/A'); ?></td> <!-- Displaying Date Ordered -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="client_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
