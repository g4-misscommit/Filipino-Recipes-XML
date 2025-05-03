<?php
include('db.php');

$username = 'admin';
$password = 'letmein';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin_users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashedPassword);
if ($stmt->execute()) {
  echo "Admin user created!";
} else {
  echo "Error: " . $conn->error;
}
?>
