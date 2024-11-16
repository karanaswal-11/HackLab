<?php
include '../config/config.php';

if ($conn) {
    echo "Connected successfully to the database!";
} else {
    echo "Connection failed: " . $conn->connect_error;
}
?>
