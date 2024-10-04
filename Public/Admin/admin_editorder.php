<?php
session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?error=You must log in as an admin to access this page.");
    exit();
}

require_once '../../Configuration/Database.php'; // Adjust the path if necessary

use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database();
$conn = $db->getConnection();

$order_id = $_GET['order_id'] ?? null;

if ($order_id) {
    // Fetch order details
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = :order_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission for updating order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'] ?? null;
    $address = $_POST['address'] ?? null;
    $contact_info = $_POST['contact_info'] ?? null;
    $status = $_POST['status'] ?? null; // Get status from POST data

    $update_stmt = $conn->prepare("UPDATE orders SET client_id = :client_id, address = :address, contact_info = :contact_info, status = :status WHERE order_id = :order_id");
    $update_stmt->bindParam(':client_id', $client_id);
    $update_stmt->bindParam(':address', $address);
    $update_stmt->bindParam(':contact_info', $contact_info);
    $update_stmt->bindParam(':status', $status); // Bind status
    $update_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $update_stmt->execute();

    header("Location: admin_manageorder.php"); // Redirect to manage orders page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Order</h2>
    <?php if ($order): ?>
        <form method="post">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
            <div class="form-group">
                <label for="client_id">Client ID</label>
                <input type="text" class="form-control" id="client_id" name="client_id" value="<?php echo htmlspecialchars($order['client_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($order['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_info">Contact Info</label>
                <input type="text" class="form-control" id="contact_info" name="contact_info" value="<?php echo htmlspecialchars($order['contact_info']); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" class="form-control" id="status" name="status" value="<?php echo htmlspecialchars($order['status']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Order</button>
            <a href="admin_manageorder.php" class="btn btn-secondary">Cancel</a>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Order not found.</div>
    <?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>