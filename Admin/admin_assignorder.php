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

// Logic to assign orders
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Order</title>
</head>
<body>
    <h1>Assign Order</h1>
    <!-- Your form and logic to assign orders -->
</body>
</html>
