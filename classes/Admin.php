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
}