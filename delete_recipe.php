<?php
include('db.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Delete from DB
    $conn->query("DELETE FROM recipes WHERE id = $id");

    // Rebuild the XML file after deletion
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

    $xml->asXML('recipes.xml');
}

header("Location: admin.php");
exit;
?>
