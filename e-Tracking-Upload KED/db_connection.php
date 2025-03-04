<?php
// Database connection setup
$host = 'localhost'; // Database host
$user = 'root'; // Database username
$pass = ''; // Database password (default for XAMPP)
$dbName = 'ked-tracking-pnn'; // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
