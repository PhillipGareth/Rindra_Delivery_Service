<?php
namespace RINDRA_DELIVERY_SERVICE\Database;

class Database
{
    private $host = 'localhost';
    private $dbname = 'rindra_delivery_db';
    private $username = 'phillipgareth';
    private $password = 'phillipgareth';
    private $connection;

    // Get the database connection
    public function getConnection()
    {
        $this->connection = null;

        try {
            $this->connection = new \PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \Exception("Connection failed: " . $e->getMessage());
        }

        return $this->connection;
    }
}
?>
