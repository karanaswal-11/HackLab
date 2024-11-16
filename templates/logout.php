<?php
session_start(); // Start session management

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit; // Stop further execution
?>
