<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

$error = '';

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

    // If login fails for all roles
    $error = "Invalid email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif; 
            background-color: #3498db; /* Background color */
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
        }
        .container {
            max-width: 400px; /* Set max width of the form */
            background: #fff; 
            padding: 40px; /* Increased padding for spaciousness */
            border-radius: 10px; 
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center; 
            margin-bottom: 20px;
            color: #333; /* Heading color */
        }
        .error { 
            color: red; 
            text-align: center; 
            margin-bottom: 15px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; /* Bold labels */
            color: #555; /* Label color */
        }
        input[type="email"], input[type="password"] { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 15px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 16px; /* Increased font size for inputs */
        }
        input[type="submit"] { 
            background-color: #2980b9; 
            color: white; 
            border: none; 
            padding: 12px; 
            border-radius: 5px; 
            cursor: pointer; 
            width: 100%; 
            font-size: 16px; /* Increased font size for button */
        }
        input[type="submit"]:hover { 
            background-color: #1c6694; 
        }
        p {
            text-align: center; 
            margin-top: 20px; 
        }
        a {
            color: #3498db; 
            text-decoration: none; 
            font-weight: bold; /* Bold links */
        }
        a:hover {
            text-decoration: underline; /* Underline on hover */
        }
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
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>
