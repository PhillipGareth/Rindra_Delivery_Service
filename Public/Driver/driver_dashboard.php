<?php
namespace RINDRA_DELIVERY_SERVICE\Public\Driver; // Namespace declaration

// Start the session only if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the Database.php file exists before including
if (!file_exists(__DIR__ . '/../../Configuration/Database.php')) {
    die("Database.php file not found!");
}

// Include necessary files from the correct path
require_once __DIR__ . '/../../Configuration/Database.php'; // Correct path to Database.php
require_once __DIR__ . '/../../classes/Driver.php'; // Correct path to Driver class

use RINDRA_DELIVERY_SERVICE\Database\Database;
use RINDRA_DELIVERY_SERVICE\Driver\Driver;

// Initialize database connection
$db = new Database();
$conn = $db->getConnection();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header('Location: ../index.php'); // Redirect to login if not logged in
    exit();
}

// Create Driver object
$driver = new Driver($conn);

// Get driver details
$driverId = $_SESSION['user_id'];
$driverDetails = $driver->getDriverById($driverId); // Retrieve driver details

if (!$driverDetails) {
    die("Driver not found."); // Handle case where driver does not exist
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .driver-info {
            margin-top: 20px;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Driver Dashboard</h2>
    <div class="driver-info">
        <h3>Welcome, <?php echo htmlspecialchars($driverDetails['username']); ?></h3>
        <p><strong>Driver ID:</strong> <?php echo htmlspecialchars($driverDetails['id']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($driverDetails['email']); ?></p>
    </div>
    <p style="text-align: center;"><a href="../index.php?logout=true">Logout</a></p> <!-- Logout link -->
</div>

</body>
</html>