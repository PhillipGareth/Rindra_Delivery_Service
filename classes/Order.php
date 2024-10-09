<?php

namespace RINDRA_DELIVERY_SERVICE\Order;

use PDO;
use Exception;

class Order {
    private $connection;

    public function __construct($db) {
        $this->connection = $db;
    }

    // Method to get orders with search and filter functionality
    public function getOrdersWithSearchAndFilter($search, $statusFilter, $driverFilter, $limit, $offset) {
        try {
            $query = "SELECT o.*, c.client_name, d.driver_name 
                      FROM orders o 
                      JOIN clients c ON o.client_id = c.id 
                      LEFT JOIN drivers d ON o.driver_id = d.id 
                      WHERE 1=1";

            // Prepare parameters array
            $params = [];

            if (!empty($search)) {
                $query .= " AND (c.client_name LIKE :search OR d.driver_name LIKE :search)";
                $params[':search'] = '%' . $search . '%'; // bind search param
            }
            if (!empty($statusFilter)) {
                $query .= " AND o.status = :status";
                $params[':status'] = $statusFilter; // bind status param
            }
            if (!empty($driverFilter)) {
                $query .= " AND d.driver_name = :driver";
                $params[':driver'] = $driverFilter; // bind driver param
            }

            // Add limit and offset for pagination
            $query .= " LIMIT :limit OFFSET :offset";

            // Prepare the statement
            $stmt = $this->connection->prepare($query);

            // Bind all parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            // Bind limit and offset as integers
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching orders: " . $e->getMessage());
        }
    }

    // Method to count total orders with search and filter functionality
    public function getTotalOrdersCountWithSearchAndFilter($search, $statusFilter, $driverFilter) {
        try {
            $query = "SELECT COUNT(*) as total 
                      FROM orders o 
                      JOIN clients c ON o.client_id = c.id 
                      LEFT JOIN drivers d ON o.driver_id = d.id 
                      WHERE 1=1";

            $params = [];

            if (!empty($search)) {
                $query .= " AND (c.client_name LIKE :search OR d.driver_name LIKE :search)";
                $params[':search'] = '%' . $search . '%'; // bind search param
            }
            if (!empty($statusFilter)) {
                $query .= " AND o.status = :status";
                $params[':status'] = $statusFilter; // bind status param
            }
            if (!empty($driverFilter)) {
                $query .= " AND d.driver_name = :driver";
                $params[':driver'] = $driverFilter; // bind driver param
            }

            $stmt = $this->connection->prepare($query);

            // Bind all parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            // Execute the statement
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (Exception $e) {
            throw new Exception("Error fetching order count: " . $e->getMessage());
        }
    }

    // Method to get all distinct order statuses
    public function getAllOrderStatuses() {
        try {
            $query = "SELECT DISTINCT status FROM orders";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            throw new Exception("Error fetching statuses: " . $e->getMessage());
        }
    }

    // Method to get all drivers
    public function getAllDrivers() {
        try {
            $query = "SELECT DISTINCT driver_name FROM drivers";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching drivers: " . $e->getMessage());
        }
    }
}
