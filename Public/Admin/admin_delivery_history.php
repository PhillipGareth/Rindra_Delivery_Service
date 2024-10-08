<?php
session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?error=You must log in as an admin to access this page.");
    exit();
}

require_once '../../Configuration/Database.php';
use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database();
$conn = $db->getConnection();

// Check if a search has been submitted
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Fetch all orders with optional search filters
$sql = "SELECT order_id, client_id, driver_id, address, contact_info, status, client_name, driver_name, date_ordered 
        FROM orders 
        WHERE (client_name LIKE :search OR driver_name LIKE :search OR DATE(date_ordered) LIKE :search)
        ORDER BY date_ordered DESC";
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $search . '%';
$stmt->bindParam(':search', $searchTerm);
//$stmt->execute();
$deliveries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Delivery History</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
        }
        .dashboard-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
        .card {
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="dashboard-header">
        <h1>Delivery History</h1>
    </div>

    <form method="GET" class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="Search by Client/Driver name or Delivery date" value="<?= htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary mt-2">Search</button>
    </form>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Delivery Records</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Client Name</th>
                        <th>Driver Name</th>
                        <th>Address</th>
                        <th>Contact Info</th>
                        <th>Status</th>
                        <th>Date Ordered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($deliveries): ?>
                        <?php foreach ($deliveries as $delivery): ?>
                            <tr>
                                <td><?= htmlspecialchars($delivery['order_id']); ?></td>
                                <td><?= htmlspecialchars($delivery['client_name']); ?></td>
                                <td><?= htmlspecialchars($delivery['driver_name']); ?></td>
                                <td><?= htmlspecialchars($delivery['address']); ?></td>
                                <td><?= htmlspecialchars($delivery['contact_info']); ?></td>
                                <td><?= htmlspecialchars($delivery['status']); ?></td>
                                <td><?= htmlspecialchars(date('Y-m-d H:i:s', strtotime($delivery['date_ordered']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No delivery history found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="admin_dashboard.php" class="btn btn-secondary">Return to Dashboard</a>
    <a href="../logout.php" class="btn btn-danger">Logout</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
