<?php
session_start(); // Start the session

// Ensure the user is logged in and has the 'client' role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php'); // Redirect non-client users or unauthorized access
    exit;
}

// Check if the client's ID is set
if (!isset($_SESSION['id'])) {
    echo "Error: Client ID not found in session.";
    exit;
}

// Include the Database class
require_once __DIR__ . '/../Database/Database.php'; // Adjust the path if necessary

// Use the Database class
use RINDRA_DELIVERY_SERVICE\Database\Database;

// Create a new instance of the Database class
$database = new Database();

// Fetch orders for the logged-in client
$client_id = $_SESSION['id']; // Get the client's ID from the session
$query = "SELECT * FROM orders WHERE client_id = ?";
$stmt = $database->prepare($query);
$stmt->bind_param("i", $client_id); // Bind the parameter for the prepared statement
$stmt->execute(); // Execute the statement
$result = $stmt->get_result(); // Get the result set
$orders = $result->fetch_all(MYSQLI_ASSOC); // Fetch all orders as an associative array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Your Orders</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Address</th>
                    <th>Contact Info</th>
                    <th>Driver ID</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['address']); ?></td>
                            <td><?php echo htmlspecialchars($order['contact_info']); ?></td>
                            <td><?php echo htmlspecialchars($order['driver_id']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="client_portal.php" class="btn btn-primary">Back to Client Portal</a>
    </div>
</body>
</html>