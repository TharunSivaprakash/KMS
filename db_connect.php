<?php
$servername = "localhost"; // XAMPP default
$username = "root"; // Default username for XAMPP
$password = "12345"; // No password for root user in XAMPP
$database = "project"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
