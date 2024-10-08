<?php
namespace RINDRA_DELIVERY_SERVICE\Client;

use PDO;
use Exception;

class Client {
    private $connection;

    public function __construct(PDO $dbConnection) {
        $this->connection = $dbConnection;
    }

    // Method for client login
    public function login($email, $password) {
        $client = $this->getClientByEmail($email);
        if ($client && password_verify($password, $client['password'])) {
            return true; // Login successful
        }
        return false; // Login failed
    }

    // Method to create a new client
    public function createUser($email, $password, $clientName) {
        try {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL statement
            $stmt = $this->connection->prepare("INSERT INTO clients (email, password, client_name) VALUES (:email, :password, :client_name)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':client_name', $clientName);

            // Execute the statement
            return $stmt->execute(); // Returns true on success
        } catch (Exception $e) {
            throw new Exception("Error creating client: " . $e->getMessage());
        }
    }

    // Method to get client ID by email
    public function getIdByEmail($email) {
        try {
            $stmt = $this->connection->prepare("SELECT id FROM clients WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $client = $stmt->fetch(PDO::FETCH_ASSOC);
            return $client['id'] ?? null; // Return client ID or null if not found
        } catch (Exception $e) {
            throw new Exception("Error fetching client ID: " . $e->getMessage());
        }
    }

    // Method to get client details by email
    private function getClientByEmail($email) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM clients WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Return client details
        } catch (Exception $e) {
            throw new Exception("Error fetching client by email: " . $e->getMessage());
        }
    }

    // Method to fetch all orders for a specific client
    public function getAllOrders($clientId) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM orders WHERE client_id = :client_id");
            $stmt->bindParam(':client_id', $clientId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all orders for the client
        } catch (Exception $e) {
            throw new Exception("Error fetching all orders: " . $e->getMessage());
        }
    }

    // Method to fetch orders for a specific client with pagination
    public function getOrdersByClientId($clientId, $limit, $offset) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM orders WHERE client_id = :client_id LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':client_id', $clientId);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return orders
        } catch (Exception $e) {
            throw new Exception("Error fetching orders: " . $e->getMessage());
        }
    }

    // Method to get total order count for pagination
    public function getTotalOrdersCount($clientId) {
        try {
            $stmt = $this->connection->prepare("SELECT COUNT(*) AS total FROM orders WHERE client_id = :client_id");
            $stmt->bindParam(':client_id', $clientId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0; // Return total count
        } catch (Exception $e) {
            throw new Exception("Error fetching total orders count: " . $e->getMessage());
        }
    }

    // Additional methods can be added here as needed
}
