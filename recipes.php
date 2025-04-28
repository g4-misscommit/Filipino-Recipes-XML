<?php
// Include the database connection
include('db.php');

// Query to get all recipes
$sql = "SELECT * FROM recipes";
$result = $conn->query($sql);

// Fetch and display the results
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='recipe-card'>";
        echo "<img src='images/" . $row['image'] . "' alt='" . $row['title'] . "'>";
        echo "<h3>" . $row['title'] . "</h3>";
        echo "<p>" . $row['category'] . "</p>";
        echo "<a href='recipe_detail.php?id=" . $row['id'] . "'>View Recipe</a>";
        echo "</div>";
    }
} else {
    echo "No recipes found!";
}

$conn->close();
?>
