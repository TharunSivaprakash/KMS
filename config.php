<?php
// Securely store API key
define("AI_API_KEY", "AIzaSyBTt0Nw-U5diLZ3Yo4itDJ61IwLj1r19mI");

// Database Connection
$servername = "localhost";
$username = "root";  // Change if needed
$password = "12345";  // Change if needed
$dbname = "project";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
