<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=You must log in as admin to access this page.");
    exit();
}

// Include the necessary files
require_once '../../Configuration/Database.php';
require_once '../../classes/Admin.php';
require_once '../../classes/Order.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;
use RINDRA_DELIVERY_SERVICE\Admin\Admin;
use RINDRA_DELIVERY_SERVICE\Order\Order;

// Create database connection
$db = new Database();
$conn = $db->getConnection();
$admin = new Admin($conn);
$order = new Order($conn);

// Handle search and filter form submissions
$search = isset($_GET['search']) ? $_GET['search'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$driverFilter = isset($_GET['driver']) ? $_GET['driver'] : '';

// Pagination settings
$limit = 10; // Number of orders per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch orders based on search and filter criteria
try {
    $orders = $order->getOrdersWithSearchAndFilter($search, $statusFilter, $driverFilter, $limit, $offset);
    $totalOrders = $order->getTotalOrdersCountWithSearchAndFilter($search, $statusFilter, $driverFilter);
    $totalPages = ceil($totalOrders / $limit);
    
    // Fetch all distinct order statuses and drivers for filter dropdowns
    $statuses = $order->getAllOrderStatuses();
    $drivers = $order->getAllDrivers();
} catch (Exception $e) {
    echo "Error fetching orders: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View Orders</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
        }
        .filter-form {
            margin-bottom: 20px;
        }
        .pagination {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="dashboard-header">
    <h1>Admin View Orders</h1>
</div>

<div class="container content">
    <form method="GET" class="filter-form">
        <div class="form-row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by client or driver name" value="<?php echo htmlspecialchars($search ?? '', ENT_QUOTES); ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">Filter by status</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?php echo htmlspecialchars($status ?? '', ENT_QUOTES); ?>" <?php echo $statusFilter === $status ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars(ucfirst($status ?? ''), ENT_QUOTES); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="driver" class="form-control">
                    <option value="">Filter by driver</option>
                    <?php foreach ($drivers as $driver): ?>
                        <option value="<?php echo htmlspecialchars($driver['driver_name'] ?? '', ENT_QUOTES); ?>" <?php echo $driverFilter === $driver['driver_name'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($driver['driver_name'] ?? '', ENT_QUOTES); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">Search</button>
            </div>
        </div>
    </form>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Client Name</th>
                <th>Address</th>
                <th>Contact Info</th>
                <th>Status</th>
                <th>Driver</th>
                <th>Date Ordered</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $orderItem): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($orderItem['order_id'] ?? '', ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($orderItem['client_name'] ?? '', ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($orderItem['address'] ?? '', ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($orderItem['contact_info'] ?? '', ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($orderItem['status'] ?? ''), ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($orderItem['driver_name'] ?? '', ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($orderItem['date_ordered'] ?? '')), ENT_QUOTES); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>&driver=<?php echo urlencode($driverFilter); ?>">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>&driver=<?php echo urlencode($driverFilter); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>&driver=<?php echo urlencode($driverFilter); ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Return to Dashboard</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
