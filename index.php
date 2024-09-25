<?php
require_once __DIR__ . '/../classes/Admin.php';

use RINDRA_DELIVERY_SERVICE\Admin\Admin;

$admin = new Admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailOrUsername = $_POST['email_or_username'];
    $password = $_POST['password'];

    try {
        // Attempt to login
        $admin->login($emailOrUsername, $password);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rindra Delivery Service</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        header { background-color: #343a40; color: white; padding: 10px; text-align: center; }
        .container { max-width: 400px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <header>
        <h1>Rindra Delivery Service</h1>
    </header>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="email_or_username" placeholder="Email or Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>