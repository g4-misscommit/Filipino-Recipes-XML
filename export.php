<?php
include('db.php');

// Fetch all recipes from database
$result = $conn->query("SELECT * FROM recipes");
$xml = new SimpleXMLElement('<recipes/>');

while ($row = $result->fetch_assoc()) {
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

// Output as download
Header('Content-type: text/xml');
Header('Content-Disposition: attachment; filename="recipes_export.xml"');
echo $xml->asXML();
exit;
?>
