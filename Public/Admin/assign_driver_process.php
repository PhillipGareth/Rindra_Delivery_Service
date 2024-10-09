<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?error=You must log in as an admin to access this page.");
    exit();
}

require_once '../../Configuration/Database.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $driver_id = $_POST['driver_id'];

    // Fetch the driver's name based on the selected driver ID
    $stmt = $conn->prepare("SELECT driver_name FROM drivers WHERE id = :driver_id");
    $stmt->execute([':driver_id' => $driver_id]);
    $driver = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($driver) {
        $driver_name = $driver['driver_name'];

        // Update the order with the assigned driver ID and name
        $update_stmt = $conn->prepare("UPDATE orders SET driver_id = :driver_id, driver_name = :driver_name WHERE order_id = :order_id");
        $update_stmt->execute([
            ':driver_id' => $driver_id,
            ':driver_name' => $driver_name,
            ':order_id' => $order_id
        ]);

        header("Location: admin_assigndriver.php?success=Driver assigned successfully.");
    } else {
        header("Location: admin_assigndriver.php?error=Driver not found.");
    }
}
?>
