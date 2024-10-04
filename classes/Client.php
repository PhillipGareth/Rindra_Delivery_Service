<?php
namespace RINDRA_DELIVERY_SERVICE\Client;

use PDO;
use Exception;

class Client {
    private $connection;
    private $id;
    private $email;
    private $password;
    private $role;

    // Constructor
    public function __construct(PDO $dbConnection, $id = null, $email = null, $password = null, $role = 'client') {
        $this->connection = $dbConnection; // Use provided connection
        $this->id = $id;
        $this->email = $email;
        $this->password = $password; // Ideally, store hashed passwords
        $this->role = $role;
    }

    // Method to get client by email
    public function getClientByEmail($email) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM clients WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching client by email: " . $e->getMessage());
        }
    }

    // Method to create a new client
    public function createClient($email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->connection->prepare("INSERT INTO clients (email, password) VALUES (:email, :password)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error creating client: " . $e->getMessage());
        }
    }

    // Method for client login
    public function login($email, $password) {
        $client = $this->getClientByEmail($email);
        if ($client && password_verify($password, $client['password'])) {
            $this->id = $client['id']; // Set the ID upon successful login
            return true;
        }
        return false;
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

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    // Additional methods as required...
}