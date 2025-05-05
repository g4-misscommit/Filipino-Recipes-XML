<?php
include('db.php');

// Fetch all recipes from database
$result = $conn->query("SELECT * FROM recipes");
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><recipes/>');

// Add XSL stylesheet reference
$dom = dom_import_simplexml($xml)->ownerDocument;
$pi = $dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="export.xsl"');
$dom->insertBefore($pi, $dom->firstChild);

// Build the XML
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

// Save to file in exports folder
$savePath = __DIR__ . '/exports/recipes_export.xml';
$dom->save($savePath);

// Force browser to download the file
header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="recipes_export.xml"');
readfile($savePath);
exit;
?>
