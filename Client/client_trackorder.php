<?php
session_start();
require_once __DIR__ . '/../classes/Client.php';
require_once __DIR__ . '/../Database/Database.php';

use RINDRA_DELIVERY_SERVICE\Client\Client;

$client = new Client();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: ../Public/login.php');
    exit;
}

// Logic for clients to track orders
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Order</title>
</head>
<body>
    <h1>Track Your Order</h1>
    <!-- Your logic to track orders -->
</body>
</html>
