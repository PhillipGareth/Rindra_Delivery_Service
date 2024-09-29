<?php
require_once '../classes/Admin.php';

use RINDRA_DELIVERY_SERVICE\Admin\Admin;

$admin = new Admin();

if (isset($_POST['create'])) {
    $clientId = $_POST['client_id'];
    $address = $_POST['address'];
    $contactInfo = $_POST['contact_info'];

    if ($admin->createOrder($clientId, $address, $contactInfo)) {
        echo "Order created successfully.";
    } else {
        echo "Failed to create order.";
    }
}

// Fetch the list of clients for the form
$clients = $admin->getAllClients();
?>

<!-- HTML form for creating an order -->
<form method="POST" action="">
    <label for="client_id">Select Client:</label>
    <select name="client_id">
        <?php foreach ($clients as $client): ?>
            <option value="<?php echo $client['id']; ?>"><?php echo $client['username']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="address">Address:</label>
    <input type="text" name="address" required>

    <label for="contact_info">Contact Info:</label>
    <input type="text" name="contact_info" required>

    <input type="submit" name="create" value="Create Order">
</form>