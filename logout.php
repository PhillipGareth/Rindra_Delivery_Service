<?php
session_start();

// Clear session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: index.php');
exit;
?>
