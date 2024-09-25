<?php
session_start();
require_once __DIR__ . '/../classes/Admin.php';
require_once __DIR__ . '/../Database/Database.php';

use RINDRA_DELIVERY_SERVICE\Admin\Admin;

$admin = new Admin();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Public/login.php');
    exit;
}

// Logic to display all orders
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Orders</title>
</head>
<body>
    <h1>All Orders</h1>
    <!-- Your logic to display all orders -->
</body>
</html>
