<?php
require_once '../classes/Admin.php';

use RINDRA_DELIVERY_SERVICE\Admin\Admin;

$admin = new Admin();
$orders = $admin->getAllOrders();
$clients = $admin->getAllClients();
$drivers = $admin->getAllDrivers();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <h2>Orders</h2>
    <ul>
    <?php foreach ($orders as $order): ?>
        <li>Order ID: <?php echo $order['order_id']; ?> - Address: <?php echo $order['address']; ?></li>
    <?php endforeach; ?>
    </ul>

    <h2>Clients</h2>
    <ul>
    <?php foreach ($clients as $client): ?>
        <li><?php echo $client['username']; ?></li>
    <?php endforeach; ?>
    </ul>

    <h2>Drivers</h2>
    <ul>
    <?php foreach ($drivers as $driver): ?>
        <li><?php echo $driver['username']; ?></li>
    <?php endforeach; ?>
    </ul>
</body>
</html>