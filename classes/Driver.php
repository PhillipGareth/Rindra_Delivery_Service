<?php

namespace RINDRA_DELIVERY_SERVICE\classes;

require_once __DIR__ . '/../Database/Database.php'; // Include the database connection class

class Driver
{
    private $db;

    // Constructor to initialize the database connection
    public function __construct()
    {
        // Using the Database class from the Database folder
        $this->db = new \RINDRA_DELIVERY_SERVICE\Database\Database();
    }

    // Register a new driver
    public function register($email, $password, $fullname, $address, $contactInfo)
    {
        try {
            // Check if the email already exists
            $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                throw new \Exception("Email already exists."); // Email already in use
            }

            // Hash the password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // SQL query to insert the driver
            $sql = "INSERT INTO users (email, password, fullname, address, contact_info, role) VALUES (:email, :password, :fullname, :address, :contactInfo, 'driver')";

            // Prepare the statement
            $stmt = $this->db->getConnection()->prepare($sql);

            // Bind the parameters
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':contactInfo', $contactInfo);

            // Execute the query
            if ($stmt->execute()) {
                return true; // Registration successful
            } else {
                throw new \Exception("Error registering driver."); // Registration failed
            }
        } catch (\Exception $e) {
            throw new \Exception("Failed to register driver: " . $e->getMessage()); // Handle exceptions
        }
    }

    // Login method for the driver
    public function login($email, $password)
    {
        $stmt = $this->db->getConnection()->prepare("SELECT password FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $driver = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($driver && password_verify($password, $driver['password'])) {
            return true; // Login successful
        }

        return false; // Login failed
    }

    // Get driver ID by email
    public function getIdByEmail($email)
    {
        $stmt = $this->db->getConnection()->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $driver = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $driver ? $driver['id'] : null; // Return the driver ID or null if not found
    }
}
?>
