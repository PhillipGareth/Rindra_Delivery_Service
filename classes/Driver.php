<?php
namespace RINDRA_DELIVERY_SERVICE\Driver;

require_once __DIR__ . '/../Configuration/Database.php'; // Ensure this path is correct

use RINDRA_DELIVERY_SERVICE\Database\Database;

class Driver {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function register($email, $fullname, $licenseNumber, $password) {
        // Check if email already exists
        $stmt = $this->db->prepare("SELECT * FROM drivers WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            throw new \Exception("Email already exists.");
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create new driver record
        $stmt = $this->db->prepare("INSERT INTO drivers (email, fullname, license_number, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$email, $fullname, $licenseNumber, $hashedPassword]);
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT password FROM drivers WHERE email = ?");
        $stmt->execute([$email]);
        $driver = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($driver && password_verify($password, $driver['password'])) {
            return true; // Login successful
        }

        return false; // Login failed
    }

    // Additional driver-specific methods...
}
?>