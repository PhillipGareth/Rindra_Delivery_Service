<?php
namespace RINDRA_DELIVERY_SERVICE\User;

use PDO;
use Exception;

class Order {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    // Create a new order
    public function createOrder($clientId, $address, $contactInfo, $driverId = null) {
        try {
            $query = "INSERT INTO orders (client_id, address, contact_info, driver_id) VALUES (:client_id, :address, :contact_info, :driver_id)";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':client_id', $clientId);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':contact_info', $contactInfo);
            $stmt->bindParam(':driver_id', $driverId);
            $stmt->execute();

            return $this->connection->lastInsertId(); // Return the ID of the newly created order
        } catch (Exception $e) {
            throw new Exception("Error creating order: " . $e->getMessage());
        }
    }

    // Retrieve an order by ID
    public function getOrderById($orderId) {
        try {
            $query = "SELECT * FROM orders WHERE order_id = :order_id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':order_id', $orderId);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC); // Return the order details
        } catch (Exception $e) {
            throw new Exception("Error retrieving order: " . $e->getMessage());
        }
    }

    // Retrieve all orders for a client
    public function getOrdersByClientId($clientId) {
        try {
            $query = "SELECT * FROM orders WHERE client_id = :client_id ORDER BY order_id DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':client_id', $clientId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return an array of orders
        } catch (Exception $e) {
            throw new Exception("Error retrieving orders: " . $e->getMessage());
        }
    }

    // Update an order
    public function updateOrder($orderId, $address, $contactInfo, $driverId = null) {
        try {
            $query = "UPDATE orders SET address = :address, contact_info = :contact_info, driver_id = :driver_id WHERE order_id = :order_id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':order_id', $orderId);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':contact_info', $contactInfo);
            $stmt->bindParam(':driver_id', $driverId);
            $stmt->execute();

            return $stmt->rowCount(); // Return the number of rows affected
        } catch (Exception $e) {
            throw new Exception("Error updating order: " . $e->getMessage());
        }
    }

    // Delete an order
    public function deleteOrder($orderId) {
        try {
            $query = "DELETE FROM orders WHERE order_id = :order_id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':order_id', $orderId);
            $stmt->execute();

            return $stmt->rowCount(); // Return the number of rows affected
        } catch (Exception $e) {
            throw new Exception("Error deleting order: " . $e->getMessage());
        }
    }
}