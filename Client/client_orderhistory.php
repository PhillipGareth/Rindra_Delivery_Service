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

// Logic to display order history
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
</head>
<body>
    <h1>Order History</h1>
    <!-- Your logic to display order history -->
</body>
</html>
