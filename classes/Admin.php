<?php
namespace RINDRA_DELIVERY_SERVICE\Admin;

use PDO;
use Exception;

class Admin {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    // Login method to authenticate admin
    public function login($email, $password) {
        try {
            $query = "SELECT * FROM admins WHERE email = :email LIMIT 1"; // Adjust table name as necessary
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify password (assuming passwords are hashed)
                if (password_verify($password, $admin['password'])) {
                    return true; // Authentication successful
                }
            }
            return false; // Authentication failed
        } catch (Exception $e) {
            throw new Exception("Error during login: " . $e->getMessage());
        }
    }

    // Method to get admin ID by email
    public function getIdByEmail($email) {
        try {
            $query = "SELECT id FROM admins WHERE email = :email LIMIT 1"; // Adjust table name as necessary
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            return $admin['id'] ?? null; // Return admin ID or null if not found
        } catch (Exception $e) {
            throw new Exception("Error fetching admin ID: " . $e->getMessage());
        }
    }

    // Method to fetch all clients
    public function getAllClients() {
        try {
            $query = "SELECT * FROM clients"; // Adjust table name as necessary
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return an array of clients
        } catch (Exception $e) {
            throw new Exception("Error fetching clients: " . $e->getMessage());
        }
    }

    // Method to fetch all drivers
    public function getAllDrivers() {
        try {
            $query = "SELECT * FROM drivers"; // Adjust table name as necessary
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return an array of drivers
        } catch (Exception $e) {
            throw new Exception("Error fetching drivers: " . $e->getMessage());
        }
    }

    // Method to fetch all orders
    public function getAllOrders() {
        try {
            $query = "SELECT * FROM orders"; // Adjust table name as necessary
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return an array of orders
        } catch (Exception $e) {
            throw new Exception("Error fetching orders: " . $e->getMessage());
        }
    }
}