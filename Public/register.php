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
    $driver_name = $_POST['driver_name'] ?? ''; // Changed to driver_name

    $role = $_POST['role'] ?? ''; // New field for user role

    // Determine user type and create user accordingly
    if ($role === 'client') {
        $client = new Client($conn);
        if ($client->createUser($email, $password, $driver_name)) { // Pass driver_name
            header('Location: index.php'); // Redirect to login page
            exit();
        } else {
            $error = "Registration failed for client. Please try again.";
        }
    } elseif ($role === 'admin') {
        $admin = new Admin($conn);
        if ($admin->createUser($email, $password, $driver_name)) { // Pass driver_name
            header('Location: index.php'); // Redirect to login page
            exit();
        } else {
            $error = "Registration failed for admin. Please try again.";
        }
    } elseif ($role === 'driver') {
        $driver = new Driver($conn);
        if ($driver->createUser($email, $password, $driver_name)) { // Pass driver_name
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
        body {
            font-family: Arial, sans-serif;
            background-color: #3498db; /* Blue background */
            margin: 0;
            padding: 0;
            height: 100vh; /* Full viewport height */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            position: relative; /* Relative positioning for child absolute positioning */
            max-width: 450px; /* Set a maximum width for the form */
            width: 100%; /* Allow full width for smaller screens */
            background: #fff; /* White background for the form */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }
        h2 {
            text-align: center;
            color: #333; /* Dark color for the heading */
            margin-bottom: 20px; /* Space below heading */
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold; /* Bold labels for clarity */
        }
        input[type="email"],
        input[type="password"],
        input[type="text"],
        select {
            width: 100%;
            padding: 12px; /* Adjusted padding for better touch targets */
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box; /* Include padding and border in width */
        }
        input[type="submit"] {
            background-color: #2980b9; /* Darker blue for the button */
            color: white;
            border: none;
            padding: 12px; /* Adjusted padding for button */
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s; /* Smooth transition for hover */
        }
        input[type="submit"]:hover {
            background-color: #1f618d; /* Even darker blue on hover */
        }
        p {
            text-align: center;
            margin-top: 15px; /* Space above paragraph */
        }
        a {
            color: #3498db; /* Link color to match background */
            text-decoration: none; /* Remove underline */
        }
        a:hover {
            text-decoration: underline; /* Underline on hover for emphasis */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>User Registration</h2>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="" method="post">
        <label for="driver_name">Driver Name:</label> <!-- Updated label -->
        <input type="text" name="driver_name" required> <!-- Updated input -->

        <label for="email">Email:</label>
        <input type="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        
        <label for="role">Select User Role:</label>
        <select name="role" required>
            <option value="client">Client</option>
            <option value="driver">Driver</option>
            <option value="admin">Admin</option>
        </select>

        <input type="submit" value="Register">
    </form>
    <p>Already have an account? <a href="index.php">Login here</a>.</p>
</div>

</body>
</html>
