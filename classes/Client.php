<?php

namespace RINDRA_DELIVERY_SERVICE\classes;

use PDO;

class Client {
    protected $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=rindra_delivery_db', 'phillipgareth', 'phillipgareth');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function register($email, $fullname, $address, $contactInfo, $password) {
        // Check if email already exists
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            throw new \Exception("Email already exists."); // Use Exception class from global namespace
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create new client record
        $stmt = $this->db->prepare("INSERT INTO clients (email, fullname, address, contact_info, password) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$email, $fullname, $address, $contactInfo, $hashedPassword]); // Return true/false for success
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT password FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client && password_verify($password, $client['password'])) {
            return true; // Login successful
        }

        return false; // Login failed
    }

    public function getIdByEmail($email) {
        $stmt = $this->db->prepare("SELECT id FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $client ? $client['id'] : null; // Return the client ID or null if not found
    }
}
?>
