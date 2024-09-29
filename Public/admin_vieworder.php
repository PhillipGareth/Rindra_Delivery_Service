<?php
require_once '../classes/Admin.php';

use RINDRA_DELIVERY_SERVICE\Admin\Admin;

$admin = new Admin();

if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];
    $order = $admin->getOrderById($orderId);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Order</title>
</head>
<body>
    <h1>Order Details</h1>
    <?php if ($order): ?>
        <p>Order ID: <?php echo $order['order_id']; ?></p>
        <p>Address: <?php echo $order['address']; ?></p>
        <p>Contact Info: <?php echo $order['contact_info']; ?></p>
        <p>Driver ID: <?php echo $order['driver_id'] ? $order['driver_id'] : 'Not Assigned'; ?></p>
    <?php else: ?>
        <p>No order found.</p>
    <?php endif; ?>
</body>
</html>