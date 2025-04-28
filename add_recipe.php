<?php
// Connect to database
$conn = new mysqli("localhost", "root", "Shimpaishinaide#999", "FilipinoRecipes");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$title = $_POST['title'];
$category = $_POST['category'];
$ingredients = $_POST['ingredients'];
$instructions = $_POST['instructions'];

// Insert into DB
$stmt = $conn->prepare("INSERT INTO recipes (title, category, ingredients, instructions) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $category, $ingredients, $instructions);
$stmt->execute();

// Now regenerate XML
$result = $conn->query("SELECT * FROM recipes");
$xml = new SimpleXMLElement('<recipes/>');

while($row = $result->fetch_assoc()) {
    $recipe = $xml->addChild('recipe');
    $recipe->addChild('title', htmlspecialchars($row['title']));
    $recipe->addChild('category', htmlspecialchars($row['category']));
    $recipe->addChild('ingredients', htmlspecialchars($row['ingredients']));
    $recipe->addChild('instructions', htmlspecialchars($row['instructions']));
}

// Save to XML file
$xml->asXML('recipes.xml');

// Redirect back to admin page
header("Location: admin.php");
?>
