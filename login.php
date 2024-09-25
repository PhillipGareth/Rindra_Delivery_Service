<?php
namespace RINDRA_DELIVERY_SERVICE\Public;

// Start the session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required classes
require_once __DIR__ . '/../classes/Admin.php';
require_once __DIR__ . '/../classes/Client.php';
require_once __DIR__ . '/../classes/Driver.php';

use RINDRA_DELIVERY_SERVICE\Classes\Admin;
use RINDRA_DELIVERY_SERVICE\Classes\Client;
use RINDRA_DELIVERY_SERVICE\Classes\Driver;

// Initialize variables
$email = '';
$password = '';
$errorMessage = '';

// Instantiate classes
$admin = new Admin();
$client = new Client();
$driver = new Driver();

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // Determine role based on email
        if (strpos($email, '@admin.com') !== false) {
            if ($admin->login($email, $password)) {
                $_SESSION['user_type'] = 'admin';
                $_SESSION['email'] = $email;
                header('Location: admin_dashboard.php'); // Redirect to admin dashboard
                exit;
            }
        } elseif (strpos($email, '@driver.com') !== false) {
            if ($driver->login($email, $password)) {
                $_SESSION['user_type'] = 'driver';
                $_SESSION['email'] = $email;
                header('Location: driver_dashboard.php'); // Redirect to driver dashboard
                exit;
            }
        } elseif (strpos($email, '@client.com') !== false) {
            if ($client->login($email, $password)) {
                $_SESSION['user_type'] = 'client';
                $_SESSION['email'] = $email;
                header('Location: client_dashboard.php'); // Redirect to client dashboard
                exit;
            }
        }

        // If login failed for all roles
        $errorMessage = "Invalid email or password.";
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rindra Delivery Service</title>
    <link rel="stylesheet" href="../style.css"> <!-- Link to your custom CSS -->
</head>
<body>
    <header>
        <h1>Rindra Delivery Service</h1>
    </header>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($errorMessage): ?>
            <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
