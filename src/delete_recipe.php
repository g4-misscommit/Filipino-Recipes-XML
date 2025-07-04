<?php
include('db.php');

if (isset($_POST['delete_all'])) {
    // Delete all recipes
    $conn->query("DELETE FROM recipes");
} elseif (!empty($_POST['recipe_ids'])) {
    // Delete selected recipes
    $ids = array_map('intval', $_POST['recipe_ids']);
    $idList = implode(',', $ids);
    $conn->query("DELETE FROM recipes WHERE id IN ($idList)");
}

// Rebuild XML
$result = $conn->query("SELECT * FROM recipes");
$xml = new SimpleXMLElement('<recipes/>');

while($row = $result->fetch_assoc()) {
    $recipe = $xml->addChild('recipe');
    $recipe->addAttribute('id', $row['id']);
    $recipe->addChild('title', htmlspecialchars($row['title']));
    $recipe->addChild('category', htmlspecialchars($row['category']));
    $recipe->addChild('prepTime', htmlspecialchars($row['prep_time']));

    $ingredients = $recipe->addChild('ingredients');
    foreach (explode(';', $row['ingredients']) as $item) {
        $ingredients->addChild('item', htmlspecialchars(trim($item)));
    }

    $instructions = $recipe->addChild('instructions');
    foreach (explode(';', $row['instructions']) as $step) {
        $instructions->addChild('step', htmlspecialchars(trim($step)));
    }

    $recipe->addChild('image', htmlspecialchars($row['image']));
}

$xml->asXML('data/recipes.xml');

header("Location: admin.php");
exit;
?>
