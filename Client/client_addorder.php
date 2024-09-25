<?php
session_start();
require_once __DIR__ . '/../classes/Client.php';
require_once __DIR__ . '/../classes/Admin.php';
require_once __DIR__ . '/../Database/Database.php';

use RINDRA_DELIVERY_SERVICE\Client\Client;
use RINDRA_DELIVERY_SERVICE\Admin\Admin;

$client = new Client();
$admin = new Admin();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: ../Public/login.php');
    exit;
}

// Fetch data from database
$orders = $client->getClientOrders($_SESSION['id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        header { background-color: #343a40; color: white; padding: 10px; text-align: center; }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <header>
        <h1>Rindra Delivery Service</h1>
    </header>
    <div class="container">
        <h2>Client Dashboard</h2>
        <h3>My Orders:</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Order Status</th>
                <th>Driver ID</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($orders as $order) { ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['status']; ?></td>
                <td><?php echo $order['driver_id']; ?></td>
                <td>
                    <a href="client_trackorder.php?id=<?php echo $order['id']; ?>">Track Order</a>
                    <a href="client_updateorder.php?id=<?php echo $order['id']; ?>">Update Order</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        <p><a href="client_addorder.php">Add New Order</a></p>
    </div>
</body>
</html>