<?php
session_start(); // Start the session

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require_once __DIR__ . '/../Configuration/Database.php'; 
require_once __DIR__ . '/../classes/Admin.php'; 
require_once __DIR__ . '/../classes/Client.php'; 
require_once __DIR__ . '/../classes/Driver.php'; 

use RINDRA_DELIVERY_SERVICE\Database\Database;
use RINDRA_DELIVERY_SERVICE\Admin\Admin;
use RINDRA_DELIVERY_SERVICE\Client\Client;
use RINDRA_DELIVERY_SERVICE\Driver\Driver;

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

// Check database connection
if (!$conn) {
    die("Database connection failed.");
}

// Handle form submission
$error = ''; // Initialize error message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if the user is an admin
    $admin = new Admin($conn);
    if ($admin->login($email, $password)) {
        $_SESSION['user_id'] = $admin->getIdByEmail($email);
        $_SESSION['role'] = 'admin';
        header('Location: Admin/admin_dashboard.php'); 
        exit();
    }

    // Check if the user is a client
    $client = new Client($conn);
    if ($client->login($email, $password)) {
        $_SESSION['user_id'] = $client->getIdByEmail($email);
        $_SESSION['role'] = 'client';
        header('Location: Client/client_dashboard.php'); 
        exit();
    }

    // Check if the user is a driver
    $driver = new Driver($conn);
    if ($driver->login($email, $password)) {
        $_SESSION['user_id'] = $driver->getIdByEmail($email);
        $_SESSION['role'] = 'driver';
        header('Location: Driver/driver_dashboard.php'); 
        exit();
    }

    // If login fails for all roles, set error message
    $error = "Invalid email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 20px; }
        .container { max-width: 400px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; }
        .error { color: red; }
        label { display: block; margin-bottom: 5px; }
        input[type="email"], input[type="password"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; }
        input[type="submit"] { background-color: #3498db; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; width: 100%; }
        input[type="submit"]:hover { background-color: #2980b9; }
    </style>
</head>
<body>

<div class="container">
    <h2>User Login</h2>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        
        <input type="submit" value="Login">
    </form>
    <p style="text-align: center;">Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>