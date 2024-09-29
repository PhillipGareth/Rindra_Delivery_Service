<?php
namespace RINDRA_DELIVERY_SERVICE\Admin;

require_once __DIR__ . '/../Configuration/Database.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;

class Admin {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function register($email, $username, $password) {
        $this->validateRegistrationInputs($email, $username, $password);

        if ($this->emailExists($email)) {
            throw new \Exception("Email already exists.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, username, role, password) VALUES (?, ?, 'admin', ?)";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute([$email, $username, $hashedPassword])) {
            return "Registration successful!";
        } else {
            throw new \Exception("Registration failed. Please try again.");
        }
    }

    private function validateRegistrationInputs($email, $username, $password) {
        if (empty($email) || empty($username) || empty($password)) {
            throw new \Exception("All fields are required.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format.");
        }
        if (strlen($password) < 6) {
            throw new \Exception("Password must be at least 6 characters long.");
        }
    }

    private function emailExists($email) {
        $checkSql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($checkSql);
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    public function login($emailOrUsername, $password) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$emailOrUsername, $emailOrUsername]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                return $user['role'];
            } else {
                throw new \Exception("Invalid password.");
            }
        } else {
            throw new \Exception("User not found.");
        }
    }

    public function logout() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php");
        exit();
    }

    public function getAllClients() {
        $sql = "SELECT * FROM users WHERE role = 'client'";
        $stmt = $this->db->query($sql);
        return $stmt; // Return the clients
    }

    public function getAllDrivers() {
        $sql = "SELECT * FROM users WHERE role = 'driver'";
        $stmt = $this->db->query($sql);
        return $stmt; // Return the drivers
    }

    public function getIdByEmail($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn();
    }

    // New methods for managing orders and drivers
    public function assignDriver($orderId, $driverId) {
        $sql = "UPDATE orders SET driver_id = :driver_id WHERE order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':driver_id', $driverId);
        $stmt->bindParam(':order_id', $orderId);
        return $stmt->execute();
    }

    public function createOrder($clientId, $address, $contactInfo) {
        $sql = "INSERT INTO orders (client_id, address, contact_info) VALUES (:client_id, :address, :contact_info)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':client_id', $clientId);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':contact_info', $contactInfo);
        return $stmt->execute();
    }

    public function getAllOrders() {
        $sql = "SELECT * FROM orders";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOrderById($orderId) {
        $sql = "SELECT * FROM orders WHERE order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
?>