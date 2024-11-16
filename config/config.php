<?php
$servername = "localhost"; // Your server name
$username = "root";         // Default XAMPP username
$password = "";             // Default XAMPP password (leave empty)
$dbname = "hacklab";       // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default username and password
define('DEFAULT_USERNAME', 'admin'); // Your default username
define('DEFAULT_PASSWORD', 'password');  // Your default password
?>
