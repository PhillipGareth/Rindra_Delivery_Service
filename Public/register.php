<?php
// register.php

require_once '../Configuration/Database.php'; // Adjust this path if necessary

use RINDRA_DELIVERY_SERVICE\Database\Database; // Use the correct namespace

// Start the session
session_start();

// Create a new database connection
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Validate input
    $username = validate($_POST['uname']);
    $email = validate($_POST['email']);
    $pass = validate($_POST['password']);
    $role = validate($_POST['role']); // Get the selected role

    // Check for empty fields
    if (empty($username)) {
        header("Location: register.php?error=User Name is required");
        exit();
    } else if (empty($email)) {
        header("Location: register.php?error=Email is required");
        exit();
    } else if (empty($pass)) {
        header("Location: register.php?error=Password is required");
        exit();
    } else if (empty($role)) {
        header("Location: register.php?error=Role is required");
        exit();
    }

    // Check if user already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        header("Location: register.php?error=Email already exists");
        exit();
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $hashedPassword = $db->hashPassword($pass);

    // Bind parameters and execute
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $hashedPassword);
    $stmt->bindParam(4, $role); // Bind the role parameter

    if ($stmt->execute()) {
        // Retrieve the user ID and set session variables
        $userId = $conn->lastInsertId();
        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $role; // Set the user's role

        // Redirect to the dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        header("Location: register.php?error=Something went wrong. Please try again.");
        exit();
    }
}
?>

<!-- HTML form for registration -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 400px;
        margin: 100px auto;
        padding: 20px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #333;
    }
    .error {
        color: red;
        text-align: center;
    }
    .form-group {
        margin: 15px 0;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    input[type="text"], input[type="password"], input[type="email"], select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
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
</div>