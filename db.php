<?php
// Database connection settings
$host = 'localhost'; // or your database server address
$user = 'root';      // your database username
$password = '';      // your database password
$dbname = 'simplytaste'; // your database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
