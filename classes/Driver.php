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

    // Method to get driver details by ID
    public function getDriverById($id) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM drivers WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Return driver details
        } catch (Exception $e) {
            throw new Exception("Error fetching driver by ID: " . $e->getMessage());
        }
    }

    // Method to create a new driver
    public function createUser($email, $password, $driver_name) { // Change parameter name to driver_name
        try {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL statement
            $stmt = $this->connection->prepare("INSERT INTO drivers (email, password, driver_name) VALUES (:email, :password, :driver_name)"); // Use driver_name
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':driver_name', $driver_name); // Bind the correct parameter

            // Execute the statement
            return $stmt->execute(); // Returns true on success
        } catch (Exception $e) {
            throw new Exception("Error creating driver: " . $e->getMessage());
        }
    }

    // Additional methods can be added here as needed
}
