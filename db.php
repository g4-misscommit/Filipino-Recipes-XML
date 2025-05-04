<?php
// Database connection settings
$host = 'localhost'; // or your database server address
$user = 'root';      // your database username
$password = 'Janet.2001';      // your database password
$dbname = 'filipinorecipes'; // your database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
