<?php
namespace RINDRA_DELIVERY_SERVICE\Database;

// Define database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // Add your password if applicable
define('DB_NAME', 'rindra_delivery_db');

class Database {
    private $host = DB_HOST; 
    private $db_name = DB_NAME;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $pdo;

    public function __construct() {
        $this->connect();
    }

    // Connect to the database using PDO
    private function connect() {
        $this->pdo = null;
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $this->pdo = new \PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            throw new \Exception("Connection error: " . $exception->getMessage());
        }
    }

    // Return the PDO connection instance
    public function getConnection() {
        return $this->pdo;
    }

    // Execute a query with parameters
    public function query($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Execute a query for insert, update, delete
    public function execute($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }

    // Hash a password using bcrypt
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Create a new admin user
    public function createAdmin($email, $password, $username) {
        $hashedPassword = $this->hashPassword($password);
        $query = "INSERT INTO admins (email, password, username) VALUES (:email, :password, :username)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':email' => $email,
            ':password' => $hashedPassword,
            ':username' => $username
        ]);
    }
}
