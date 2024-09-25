<?php
session_start();
require_once __DIR__ . '/../classes/Driver.php';
require_once __DIR__ . '/../Database/Database.php';

use RINDRA_DELIVERY_SERVICE\Driver\Driver;

$driver = new Driver();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'driver') {
    header('Location: ../Public/login.php');
    exit;
}

// Logic to display assigned orders to driver
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assigned Orders</title>
</head>
<body>
    <h1>Your Assigned Orders</h1>
    <!-- Your logic to display assigned orders -->
</body>
</html>
