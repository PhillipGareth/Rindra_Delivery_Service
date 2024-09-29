<?php
require_once '../classes/Admin.php';

use RINDRA_DELIVERY_SERVICE\Admin\Admin;

$admin = new Admin();

$message = '';

if (isset($_POST['assign'])) {
    $orderId = $_POST['order_id'];
    $driverId = $_POST['driver_id'];

    if ($admin->assignDriver($orderId, $driverId)) {
        $message = "Driver assigned successfully.";
    } else {
        $message = "Failed to assign driver.";
    }
}

// Fetch order details and drivers for the form
$orders = $admin->getAllOrders();
$drivers = $admin->getAllDrivers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Driver</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .form-control {
            border-radius: 0.5rem;
        }
        h1, h2 {
            margin-top: 20px;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Assign Driver to Order</h1>

    <?php if ($message): ?>
        <div class="alert alert-info" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="order_id" class="form-label">Select Order:</label>
            <select name="order_id" class="form-select" required>
                <option value="">-- Select an Order --</option>
                <?php foreach ($orders as $order): ?>
                    <option value="<?php echo $order['order_id']; ?>">Order ID: <?php echo $order['order_id']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="driver_id" class="form-label">Select Driver:</label>
            <select name="driver_id" class="form-select" required>
                <option value="">-- Select a Driver --</option>
                <?php foreach ($drivers as $driver): ?>
                    <option value="<?php echo $driver['id']; ?>"><?php echo $driver['username']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="assign" class="btn btn-primary">Assign Driver</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>