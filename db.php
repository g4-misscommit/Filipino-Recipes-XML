<?php
// Database connection settings
$host = 'localhost'; // or your database server address
$user = 'root';      // your database username
$password = 'Shimpaishinaide#999';      // your database password
$dbname = 'FilipinoRecipes'; // your database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
