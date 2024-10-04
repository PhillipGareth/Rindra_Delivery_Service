<?php
namespace RINDRA_DELIVERY_SERVICE\Database; // Namespace declaration

// Define database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // Ensure password is set correctly, if any
define('DB_NAME', 'rindra_delivery_db');

class Database {
    private $host = DB_HOST; 
    private $db_name = DB_NAME;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    public $pdo;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->pdo = null;
        try {
            $this->pdo = new \PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4", $this->username, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $exception) {
            // Log the error message
            error_log("Connection error: " . $exception->getMessage());
            throw new \Exception("Connection error: " . $exception->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo; // Return the PDO connection
    }

    public function query($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function execute($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function createAdmin($email, $password, $fullname, $role) {
        $hashedPassword = $this->hashPassword($password);
        $query = "INSERT INTO Admins (email, password, fullname, role) VALUES (:email, :password, :fullname, :role)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':email' => $email,
            ':password' => $hashedPassword,
            ':fullname' => $fullname,
            ':role' => $role
        ]);
    }
}
?>