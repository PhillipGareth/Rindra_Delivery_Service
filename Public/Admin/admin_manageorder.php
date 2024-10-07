<?php
session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?error=You must log in as an admin to access this page.");
    exit();
}

require_once '../../Configuration/Database.php'; // Adjust the path if necessary

use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database();
$conn = $db->getConnection();

// Handle delete order request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $delete_order_id = $_POST['delete_order_id'];
    $delete_stmt = $conn->prepare("DELETE FROM orders WHERE order_id = :order_id");
    $delete_stmt->bindParam(':order_id', $delete_order_id, PDO::PARAM_INT);
    $delete_stmt->execute();
    header("Location: admin_manageorder.php"); // Refresh the page to see the changes
    exit();
}

// Fetch orders from the database, including driver_name and client_name
$query = "
    SELECT o.*, d.driver_name, c.client_name 
    FROM orders o 
    LEFT JOIN drivers d ON o.driver_id = d.id 
    LEFT JOIN clients c ON o.client_id = c.id"; // Query to join orders with drivers and clients
$stmt = $conn->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Manage Orders</h1>

    <?php if (count($orders) === 0): ?>
        <div class="alert alert-info">No orders found.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Client ID</th>
                    <th>Client Name</th>
                    <th>Driver Name</th>
                    <th>Address</th>
                    <th>Contact Info</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['client_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['client_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($order['driver_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($order['address']); ?></td>
                        <td><?php echo htmlspecialchars($order['contact_info']); ?></td>
                        <td><?php echo htmlspecialchars($order['status'] ?? 'Not specified'); ?></td>
                        <td>
                            <a href="admin_editorder.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="delete_order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
