<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Handle registration form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $username = $_POST['username'] ?? '';
    $role = $_POST['role'] ?? ''; // New field for user role

    // Determine user type and create user accordingly
    if ($role === 'client') {
        $client = new Client($conn);
        if ($client->createUser($email, $password, $username)) {
            header('Location: index.php'); // Redirect to login page
            exit();
        } else {
            $error = "Registration failed for client. Please try again.";
        }
    } elseif ($role === 'admin') {
        $admin = new Admin($conn);
        if ($admin->createUser($email, $password, $username)) {
            header('Location: index.php'); // Redirect to login page
            exit();
        } else {
            $error = "Registration failed for admin. Please try again.";
        }
    } elseif ($role === 'driver') {
        $driver = new Driver($conn);
        if ($driver->createUser($email, $password, $username)) {
            header('Location: index.php'); // Redirect to login page
            exit();
        } else {
            $error = "Registration failed for driver. Please try again.";
        }
    } else {
        $error = "Invalid role selected.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 20px; }
        .container { max-width: 400px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; }
        .error { color: red; }
        label { display: block; margin-bottom: 5px; }
        input[type="email"], input[type="password"], input[type="text"], select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; }
        input[type="submit"] { background-color: #3498db; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; width: 100%; }
        input[type="submit"]:hover { background-color: #2980b9; }
    </style>
</head>
<body>

<div class="container">
    <h2>User Registration</h2>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        
        <label for="role">Select User Role:</label>
        <select name="role" required>
            <option value="client">Client</option>
            <option value="admin">Admin</option>
            <option value="driver">Driver</option>
        </select>
        
        <input type="submit" value="Register">
    </form>
    <p style="text-align: center;">Already have an account? <a href="index.php">Login here</a></p>
</div>

</body>
</html>