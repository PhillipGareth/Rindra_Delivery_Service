<?php
namespace RINDRA_DELIVERY_SERVICE\Driver;

use PDO;
use Exception;

class Driver {
    private $connection;

    public function __construct(PDO $dbConnection) {
        $this->connection = $dbConnection;
    }

    // Method for driver login
    public function login($email, $password) {
        $driver = $this->getDriverByEmail($email);
        if ($driver && password_verify($password, $driver['password'])) {
            return true; // Login successful
        }
        return false; // Login failed
    }

    // Method to create a new driver user
    public function createUser($email, $password, $driver_name) {
        try {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Prepare the SQL statement
            $stmt = $this->connection->prepare("INSERT INTO drivers (email, password, driver_name) VALUES (:email, :password, :driver_name)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':driver_name', $driver_name); // Use the correct parameter name

            // Execute the statement
            return $stmt->execute(); // Returns true on success
        } catch (Exception $e) {
            throw new Exception("Error creating driver: " . $e->getMessage());
        }
    }

    // Method to get driver ID by email
    public function getIdByEmail($email) {
        try {
            $stmt = $this->connection->prepare("SELECT id FROM drivers WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $driver = $stmt->fetch(PDO::FETCH_ASSOC);
            return $driver['id'] ?? null; // Return driver ID or null if not found
        } catch (Exception $e) {
            throw new Exception("Error fetching driver ID: " . $e->getMessage());
        }
    }

    // Method to get driver details by email
    private function getDriverByEmail($email) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM drivers WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Return driver details
        } catch (Exception $e) {
            throw new Exception("Error fetching driver by email: " . $e->getMessage());
        }
    }

    // Method to update order status
    public function updateOrderStatus($orderId, $status) {
        try {
            $stmt = $this->connection->prepare("UPDATE orders SET status = :status WHERE order_id = :orderId");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':orderId', $orderId);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error updating order status: " . $e->getMessage());
        }
    }

    // Method to fetch delivery history (orders) for the driver
    public function getDeliveryHistory($driverId) {
        try {
            // Fetch orders where the driver_id matches the logged-in driver
            $stmt = $this->connection->prepare("SELECT order_id, status FROM orders WHERE driver_id = :driverId");
            $stmt->bindParam(':driverId', $driverId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return orders assigned to the driver
        } catch (Exception $e) {
            throw new Exception("Error fetching delivery history: " . $e->getMessage());
        }
    }

    // Method to get driver details by ID
    public function getDriverById($driverId) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM drivers WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $driverId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Return driver details
        } catch (Exception $e) {
            throw new Exception("Error fetching driver by ID: " . $e->getMessage());
        }
    }
}
