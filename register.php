<?php
session_start(); // Start the session

// Include required classes
require_once __DIR__ . '/../classes/Admin.php';
require_once __DIR__ . '/../classes/Client.php'; // Include Client class
require_once __DIR__ . '/../classes/Driver.php'; // Include Driver class
require_once __DIR__ . '/../Database/Database.php'; // Include database connection

use RINDRA_DELIVERY_SERVICE\Admin\Admin;
use RINDRA_DELIVERY_SERVICE\Client\Client;
use RINDRA_DELIVERY_SERVICE\Driver\Driver;

// Initialize instances
$admin = new Admin();
$client = new Client();
$driver = new Driver();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // New field for role selection
    $fullname = $_POST['fullname']; // Full name of the user

    try {
        // Register based on user role
        if ($role === 'client') {
            $client->register($email, $password, $fullname); // Register client
            header('Location: login.php'); // Redirect to login page after successful registration
            exit;
        } elseif ($role === 'driver') {
            $driver->register($email, $password, $fullname); // Register driver
            header('Location: login.php'); // Redirect to login page after successful registration
            exit;
        } elseif ($role === 'admin') {
            $admin->register($email, $password, $fullname); // Register admin
            header('Location: login.php'); // Redirect to login page after successful registration
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage(); // Capture any errors during registration
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Rindra Delivery Service</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        header { background-color: #343a40; color: white; padding: 10px; text-align: center; }
        .container { max-width: 400px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #218838; }
        p { color: red; }
    </style>
</head>
<body>
    <header>
        <h1>Rindra Delivery Service</h1>
    </header>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="client">Client</option>
                <option value="driver">Driver</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Register</button>
        </form>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
