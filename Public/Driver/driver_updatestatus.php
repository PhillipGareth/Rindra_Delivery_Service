<?php
session_start();
require_once '../../Configuration/Database.php';
require_once '../../classes/Driver.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;
use RINDRA_DELIVERY_SERVICE\Driver\Driver;

// Check if the user is logged in as a driver
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header("Location: ../index.php?error=You must log in as a driver to access this page.");
    exit();
}

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

$driver = new Driver($conn);

// Initialize message variable for user feedback
$message = "";

// Only process the form when it is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if order_id and status are set in POST
    if (isset($_POST['order_id']) && isset($_POST['status'])) {
        $orderId = $_POST['order_id'];
        $status = $_POST['status'];

        try {
            // Update the order status
            if ($driver->updateOrderStatus($orderId, $status)) {
                $message = "Order status updated successfully.";
            } else {
                $message = "Failed to update order status.";
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    } else {
        $message = "Please select an order and status.";
    }
}

// Fetch the driver's delivery history (all orders assigned to this driver)
$driverId = $_SESSION['user_id'];
$deliveryHistory = $driver->getDeliveryHistory($driverId);

// Fetch all orders from the database for display
try {
    $stmt = $conn->prepare("SELECT * FROM orders");
    $stmt->execute();
    $allOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $message = "Error fetching orders: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order Status</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Update Order Status</h1>

    <!-- Display any message (success or error) -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Update Order Status Form -->
    <form action="" method="POST">
        <div class="form-group">
            <label for="order_id">Select Order:</label>
            <select name="order_id" id="order_id" class="form-control" required>
                <option value="">-- Select an Order --</option>
                <?php if (count($deliveryHistory) > 0): ?>
                    <?php foreach ($deliveryHistory as $order): ?>
                        <option value="<?php echo $order['order_id']; ?>">
                            Order #<?php echo $order['order_id']; ?> (Current Status: <?php echo $order['status']; ?>)
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No orders found for you.</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Update Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="">-- Select Status --</option>
                <option value="In Progress">In Progress</option>
                <option value="Delivered">Delivered</option>
                <option value="Canceled">Canceled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>

    <h2 class="mt-4">All Orders</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Client ID</th>
                <th>Address</th>
                <th>Contact Info</th>
                <th>Driver ID</th>
                <th>Status</th>
                <th>Client Name</th>
                <th>Driver Name</th>
                <th>Date Ordered</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($allOrders)): ?>
                <?php foreach ($allOrders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['client_id']; ?></td>
                        <td><?php echo $order['address']; ?></td>
                        <td><?php echo $order['contact_info']; ?></td>
                        <td><?php echo $order['driver_id']; ?></td>
                        <td><?php echo $order['status']; ?></td>
                        <td><?php echo $order['client_name']; ?></td>
                        <td><?php echo $order['driver_name']; ?></td>
                        <td><?php echo $order['date_ordered']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Back to Dashboard Button -->
    <div class="mt-3">
        <a href="driver_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
