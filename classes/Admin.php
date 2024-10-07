<?php
namespace RINDRA_DELIVERY_SERVICE\Admin;

use PDO;
use Exception;

class Admin {
    private $connection;

    public function __construct($dbConnection) {
        $this->connection = $dbConnection;
    }

    public function login($email, $password) {
        try {
            $query = "SELECT * FROM admins WHERE email = :email LIMIT 1";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $admin['password'])) {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            throw new Exception("Error during login: " . $e->getMessage());
        }
    }

    public function getIdByEmail($email) {
        try {
            $query = "SELECT id FROM admins WHERE email = :email LIMIT 1";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            return $admin['id'] ?? null;
        } catch (Exception $e) {
            throw new Exception("Error fetching admin ID: " . $e->getMessage());
        }
    }

    public function createUser($email, $password, $username) {
        try {
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO admins (email, password, username) VALUES (:email, :password, :username)";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':username', $username);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error creating admin: " . $e->getMessage());
        }
    }
}
