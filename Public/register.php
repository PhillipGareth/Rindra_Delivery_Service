<?php
// register.php

require_once '../Configuration/Database.php'; // Adjust this path if necessary

use RINDRA_DELIVERY_SERVICE\Database\Database;

// Start the session
session_start();

// Create a new database connection
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Validate input
    $username = validate($_POST['uname']);
    $email = validate($_POST['email']);
    $pass = validate($_POST['password']);
    $role = validate($_POST['role']); // Get the selected role

    // Check for empty fields
    if (empty($username) || empty($email) || empty($pass) || empty($role)) {
        header("Location: register.php?error=All fields are required");
        exit();
    }

    // Check if user already exists in both tables
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ? UNION SELECT email FROM admins WHERE email = ? UNION SELECT email FROM drivers WHERE email = ?");
    $stmt->execute([$email, $email, $email]);
    if ($stmt->rowCount() > 0) {
        header("Location: register.php?error=Email already exists");
        exit();
    }

    // Prepare SQL statement based on role
    if ($role === 'Admin') {
        $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
    } elseif ($role === 'Client') {
        $stmt = $conn->prepare("INSERT INTO clients (username, email, password) VALUES (?, ?, ?)");
    } elseif ($role === 'Driver') {
        $stmt = $conn->prepare("INSERT INTO drivers (username, email, password) VALUES (?, ?, ?)");
    } else {
        header("Location: register.php?error=Invalid role selected");
        exit();
    }

    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT); // Hash the password

    // Bind parameters and execute
    if ($stmt->execute([$username, $email, $hashedPassword])) {
        // Set session variables based on role
        $_SESSION['user_id'] = $conn->lastInsertId();
        $_SESSION['role'] = strtolower($role); // Store role in lowercase for consistency

        // Redirect to the appropriate dashboard
        header("Location: " . strtolower($role) . "_dashboard.php");
        exit();
    } else {
        header("Location: register.php?error=Registration failed");
        exit();
    }
}
?>

<!-- HTML form for registration -->
<style>
    body { font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 0; }
    .container { max-width: 400px; margin: 100px auto; padding: 20px; background-color: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    h2 { text-align: center; color: #333; }
    .error { color: red; text-align: center; }
    .form-group { margin: 15px 0; }
    label { display: block; margin-bottom: 5px; }
    input[type="text"], input[type="password"], input[type="email"], select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
    input[type="submit"] { width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
    input[type="submit"]:hover { background-color: #45a049; }
</style>

<div class="container">
    <h2>Register</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="form-group">
            <label for="uname">User Name:</label>
            <input type="text" id="uname" name="uname" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="">Select a role</option>
                <option value="Client">Client</option>
                <option value="Admin">Admin</option>
                <option value="Driver">Driver</option>
            </select>
        </div>
        <input type="submit" value="Register">
        <?php if (isset($_GET['error'])) { echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>'; } ?>
    </form>
    <p style="text-align: center;">Already have an account? <a href="index.php">Go to Login</a></p>
</div>