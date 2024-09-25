<?php
// Configuration/config.php

// Include the Database class with the correct namespace
require_once '../Database/Database.php'; // Correct path to Database.php

use RINDRA_DELIVERY_SERVICE\Database\Database; // Use the correct namespace

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create a Database instance
$db = new Database();
$conn = $db->getConnection(); // Get the database connection
