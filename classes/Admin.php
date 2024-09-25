<?php
namespace RINDRA_DELIVERY_SERVICE\classes;

require_once __DIR__ . '/../Database/Database.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;

class Admin {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createUser($email, $username, $fullname, $role, $password) {
        $sql = "INSERT INTO users (email, username, fullname, role, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssss", $email, $username, $fullname, $role, password_hash($password, PASSWORD_DEFAULT));
        return $stmt->execute(); // Return true/false for success
    }

    public function login($emailOrUsername, $password) {
        $sql = "SELECT * FROM users WHERE email = ? OR username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['fullname'] = $user['fullname'];

                // Redirect based on role after successful login
                return $user['role']; // Return role for handling in login.php
            } else {
                throw new \Exception("Invalid password.");
            }
        } else {
            throw new \Exception("User not found.");
        }
    }

    public function getAllClients() {
        $sql = "SELECT * FROM users WHERE role = 'client'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllDrivers() {
        $sql = "SELECT * FROM users WHERE role = 'driver'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
