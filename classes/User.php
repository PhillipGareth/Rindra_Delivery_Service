<?php
namespace RINDRA_DELIVERY_SERVICE\User;

require_once __DIR__ . '/../Configuration/Database.php'; // Ensure this path is correct

use RINDRA_DELIVERY_SERVICE\Database\Database;

class User {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Other common methods for users...
}
?>