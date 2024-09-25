<?php

namespace RINDRA_DELIVERY_SERVICE\User;

require_once __DIR__ . '/../Database/Database.php';

use RINDRA_DELIVERY_SERVICE\Database\Database;

class User {
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createUser($email, $username, $fullname, $password, $role = 'user') {
        // Check if PDO is initialized
        if ($this->db->getPdo() === null) {
            echo "Database connection is not established.";
            return;
        }

        try {
            // Check if the email already exists
            $stmt = $this->db->getPdo()->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                echo "Error: A user with this email already exists.";
                return; // Exit the function if email exists
            }

            // Prepare and execute the insert statement
            $stmt = $this->db->getPdo()->prepare("INSERT INTO users (email, username, fullname, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$email, $username, $fullname, $password, $role]);
            // Removed the success message
        } catch (\PDOException $e) {
            echo "Error creating user: " . $e->getMessage();
        }
    }
}
?>
