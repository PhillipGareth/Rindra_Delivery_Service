<?php
namespace RINDRA_DELIVERY_SERVICE\User;

require_once __DIR__ . '/../Configuration/Database.php'; // Ensure this path is correct

use RINDRA_DELIVERY_SERVICE\Database\Database;

class User {
    protected $db;

    public function __construct() {
        $database = new Database(); // Instantiate the Database class
        $this->db = $database->getConnection(); // Get the PDO connection
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return ($user);
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // public function getUserByEmail($email) {
    //     $sql = "SELECT * FROM users WHERE email = ?";
    //     $stmt = $this->db->prepare($sql); // Make sure you're using $this->conn, not $this->db if that's how you pass the DB connection.
    //     $stmt->execute([$email]);
    //     $user =  $stmt->fetch(\PDO::FETCH_ASSOC); // Return the fetched row as an associative array

    //     return $user;
    // }
    // Other common methods for users...
}
?>