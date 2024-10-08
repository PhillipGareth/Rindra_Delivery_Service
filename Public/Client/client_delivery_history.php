<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../index.php?error=You must log in as a client to access this page.");
    exit();
}

require_once '../../Configuration/Database.php';
use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database();
$conn = $db->getConnection();
$userId = $_SESSION['user_id'];

// Fetch all deliveries for the client
$sql = "SELECT order_id, address, contact_info, status, driver_name, date_ordered 
        FROM orders 
        WHERE client_id = :client_id 
        ORDER BY date_ordered DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':client_id', $userId);
$stmt->execute();
$deliveries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Delivery History</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Delivery History</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Address</th>
                <th>Contact Info</th>
                <th>Status</th>
                <th>Driver Name</th>
                <th>Date Ordered</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($deliveries): ?>
                <?php foreach ($deliveries as $delivery): ?>
                    <tr>
                        <td><?= htmlspecialchars($delivery['order_id']); ?></td>
                        <td><?= htmlspecialchars($delivery['address']); ?></td>
                        <td><?= htmlspecialchars($delivery['contact_info']); ?></td>
                        <td><?= htmlspecialchars($delivery['status']); ?></td>
                        <td><?= htmlspecialchars($delivery['driver_name']); ?></td>
                        <td><?= htmlspecialchars(date('Y-m-d H:i:s', strtotime($delivery['date_ordered']))); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No delivery history found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <a href="client_dashboard.php" class="btn btn-primary">Return to Dashboard</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
