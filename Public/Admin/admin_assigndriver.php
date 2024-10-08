<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?error=You must log in as an admin to access this page.");
    exit();
}

require_once '../../Configuration/Database.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database();
$conn = $db->getConnection();

// Fetch all drivers
$drivers = $conn->query("SELECT * FROM drivers")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all orders
$orders = $conn->query("SELECT * FROM orders")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Driver</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Assign Driver</h1>

    <!-- Display all orders in a table -->
    <h2>All Orders</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Address</th>
                <th>Driver ID</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="4" class="text-center">No orders available.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['address']); ?></td>
                        <td><?php echo htmlspecialchars($order['driver_id'] ? $order['driver_id'] : 'Not Assigned'); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <form action="assign_driver_process.php" method="post">
        <div class="form-group">
            <label for="order_id">Select Order:</label>
            <select id="order_id" name="order_id" class="form-control" required>
                <option value="">Select an order</option>
                <?php foreach ($orders as $order): ?>
                    <option value="<?php echo $order['order_id']; ?>">
                        <?php echo htmlspecialchars($order['address']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="driver_id">Select Driver:</label>
            <select id="driver_id" name="driver_id" class="form-control" required>
                <option value="">Select a driver</option>
                <?php foreach ($drivers as $driver): ?>
                    <option value="<?php echo $driver['id']; ?>">
                        <?php echo htmlspecialchars($driver['driver_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Assign Driver</button>
    </form>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
