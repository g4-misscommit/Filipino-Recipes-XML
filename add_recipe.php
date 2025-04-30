<?php
include('db.php');

// Get form data
$title = $_POST['title'];
$category = $_POST['category'];
$prepTime = $_POST['prepTime'];
$image = $_POST['image'];
$ingredients = $_POST['ingredients']; // this is an array
$instructions = $_POST['instructions']; // this is an array

// Convert arrays to strings for DB
$ingredientsStr = implode('; ', $ingredients);
$instructionsStr = implode('; ', $instructions);

// Insert into database
$stmt = $conn->prepare("INSERT INTO recipes (title, category, prepTime, ingredients, instructions, image) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $title, $category, $prepTime, $ingredientsStr, $instructionsStr, $image);
$stmt->execute();

// Regenerate XML from database
$result = $conn->query("SELECT * FROM recipes");
$xml = new SimpleXMLElement('<recipes/>');

while($row = $result->fetch_assoc()) {
    $recipe = $xml->addChild('recipe');
    $recipe->addAttribute('id', $row['id']);
    $recipe->addChild('title', htmlspecialchars($row['title']));
    $recipe->addChild('category', htmlspecialchars($row['category']));
    $recipe->addChild('prepTime', htmlspecialchars($row['prepTime']));

    $ingredientsEl = $recipe->addChild('ingredients');
    foreach (explode(';', $row['ingredients']) as $item) {
        $trimmed = trim($item);
        if ($trimmed) {
            $ingredientsEl->addChild('item', htmlspecialchars($trimmed));
        }
    }

    $instructionsEl = $recipe->addChild('instructions');
    foreach (explode(';', $row['instructions']) as $step) {
        $trimmed = trim($step);
        if ($trimmed) {
            $instructionsEl->addChild('step', htmlspecialchars($trimmed));
        }
    }

    $recipe->addChild('image', htmlspecialchars($row['image']));
}

$xml->asXML('recipes.xml');
header("Location: admin.php?success=1");
exit;

?>
