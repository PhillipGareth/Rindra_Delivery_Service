<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(); // Start the session if not already started
}

// Ensure that the path to your files is correct
require_once '../Configuration/Database.php';
require_once '../classes/Admin.php'; 
require_once '../classes/Client.php';
require_once '../classes/Driver.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database(); // Instantiate the Database class
$conn = $db->getConnection(); // Get the database connection

// Handle login
$loginError = ''; // Initialize login error variable
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create instances of classes with the correct namespaces
    $admin = new \RINDRA_DELIVERY_SERVICE\Admin\Admin();
    $client = new \RINDRA_DELIVERY_SERVICE\Client\Client();
    $driver = new \RINDRA_DELIVERY_SERVICE\Driver\Driver();

    try {
        // Attempt to login as an admin, client, or driver
        if ($admin->login($email, $password)) {
            $_SESSION['user_id'] = $admin->getIdByEmail($email); // Store user ID
            $_SESSION['role'] = 'admin'; // Set user role
            header('Location: admin_dashboard.php'); // Redirect to admin dashboard
            exit;
        } elseif ($client->login($email, $password)) {
            $_SESSION['user_id'] = $client->getIdByEmail($email); // Store user ID
            $_SESSION['role'] = 'client'; // Set user role
            header('Location: client_dashboard.php'); // Redirect to client dashboard
            exit;
        } elseif ($driver->login($email, $password)) {
            $_SESSION['user_id'] = $driver->getIdByEmail($email); // Store user ID
            $_SESSION['role'] = 'driver'; // Set user role
            header('Location: driver_dashboard.php'); // Redirect to driver dashboard
            exit;
        } else {
            $loginError = 'Invalid email or password'; // Error message for invalid login
        }
    } catch (Exception $e) {
        $loginError = 'Error: ' . htmlspecialchars($e->getMessage()); // Catch any exceptions
    }
}
?>

<!-- Combined HTML and CSS styles for login page -->
<style>
    body {
        background-color: #f2f2f2;
        font-family: Arial, sans-serif;
    }

    .container {
        width: 400px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        width: 100%;
        height: 40px;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .btn {
        width: 100%;
        height: 40px;
        padding: 10px;
        font-size: 16px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #3e8e41;
    }

    .register-btn {
        width: 100%;
        height: 40px;
        margin-top: 10px;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .register-btn:hover {
        background-color: #0056b3;
    }

    .error {
        color: red;
        text-align: center;
        font-weight: bold;
    }
</style>

<!-- HTML form for login -->
<div class="container">
    <h2>Login</h2>
    <?php if ($loginError): ?>
        <div class="error"><?php echo $loginError; ?></div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn">Login</button>
        <button type="button" onclick="window.location.href='register.php'" class="register-btn">Register</button> <!-- Register button -->
    </form>
</div>