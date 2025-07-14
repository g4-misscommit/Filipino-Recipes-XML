<?php
include 'db.php'; // Make sure your DB connection is included

if (isset($_POST['id'])) {
  $ids = $_POST['id'];
  $titles = $_POST['title'];
  $categories = $_POST['category'];
  $prep_times = $_POST['prep_time'];
  $ingredients = $_POST['ingredients'];
  $instructions = $_POST['instructions'];
  $images = $_POST['image'];

  for ($i = 0; $i < count($ids); $i++) {
    $id = $conn->real_escape_string($ids[$i]);
    $title = $conn->real_escape_string($titles[$i]);
    $category = $conn->real_escape_string($categories[$i]);
    $prep_time = $conn->real_escape_string($prep_times[$i]);
    $ingredient = $conn->real_escape_string($ingredients[$i]);
    $instruction = $conn->real_escape_string($instructions[$i]);
    $image = $conn->real_escape_string($images[$i]);

    $sql = "UPDATE recipes SET 
              title='$title', 
              category='$category', 
              prep_time='$prep_time',
              ingredients='$ingredient', 
              instructions='$instruction', 
              image='$image' 
            WHERE id='$id'";

    $conn->query($sql);
  }

  header("Location: admin.php?updated=1"); // Redirect back
  exit;
}
?>
