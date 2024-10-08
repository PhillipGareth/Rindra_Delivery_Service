<?php
// Configuration/session.php

// Start the session if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check user role and redirect if unauthorized
function checkRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        // Redirect unauthorized users to the login page
        header('Location: index.php');
        exit();
    }
}
?>
