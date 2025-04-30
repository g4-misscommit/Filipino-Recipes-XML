<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imported_file'])) {
    $fileTmpPath = $_FILES['imported_file']['tmp_name'];
    $fileType = $_FILES['imported_file']['type'];

    // Validate file type
    if ($fileType != 'text/xml' && $fileType != 'application/xml') {
        die("Error: Please upload a valid XML file.");
    }

    // Load uploaded XML
    $importedXml = simplexml_load_file($fileTmpPath);
    if (!$importedXml || !isset($importedXml->recipe)) {
        die("Error: Invalid XML structure.");
    }

    // Load current recipes.xml
    $currentXml = simplexml_load_file('recipes.xml');

    $lastId = 0;
    foreach ($currentXml->recipe as $r) {
        $lastId = max($lastId, (int)$r['id']);
    }

    $addedCount = 0;

    foreach ($importedXml->recipe as $newRecipe) {
        // Basic validation
        if (
            !isset($newRecipe->title) ||
            !isset($newRecipe->category) ||
            !isset($newRecipe->prepTime) ||
            !isset($newRecipe->ingredients) ||
            !isset($newRecipe->instructions) ||
            !isset($newRecipe->image)
        ) {
            continue; // Skip invalid entries
        }

        // Check for duplicates (by title)
        $titleCheck = $conn->real_escape_string((string)$newRecipe->title);
        $checkQuery = $conn->query("SELECT id FROM recipes WHERE title = '$titleCheck' LIMIT 1");
        if ($checkQuery->num_rows > 0) {
            continue; // Skip duplicate title
        }

        $lastId++;
        $recipe = $currentXml->addChild('recipe');
        $recipe->addAttribute('id', $lastId);
        $recipe->addChild('title', htmlspecialchars($newRecipe->title));
        $recipe->addChild('category', htmlspecialchars($newRecipe->category));
        $recipe->addChild('prepTime', htmlspecialchars($newRecipe->prepTime));

        $ingredients = $recipe->addChild('ingredients');
        $ingredientsArray = [];
        foreach ($newRecipe->ingredients->item as $item) {
            $ingredients->addChild('item', htmlspecialchars($item));
            $ingredientsArray[] = trim($item);
        }

        $instructions = $recipe->addChild('instructions');
        $instructionsArray = [];
        foreach ($newRecipe->instructions->step as $step) {
            $instructions->addChild('step', htmlspecialchars($step));
            $instructionsArray[] = trim($step);
        }

        $recipe->addChild('image', htmlspecialchars($newRecipe->image));

        // Insert to DB as well
        $stmt = $conn->prepare("INSERT INTO recipes (title, category, prepTime, ingredients, instructions, image) VALUES (?, ?, ?, ?, ?, ?)");
        $title = (string)$newRecipe->title;
        $category = (string)$newRecipe->category;
        $prepTime = (string)$newRecipe->prepTime;
        $image = (string)$newRecipe->image;
        $ingredientsStr = implode('; ', $ingredientsArray);
        $instructionsStr = implode('; ', $instructionsArray);
        $stmt->bind_param("ssssss", $title, $category, $prepTime, $ingredientsStr, $instructionsStr, $image);
        $stmt->execute();

        $addedCount++;
    }

    // Save updated XML
    $currentXml->asXML('recipes.xml');

    echo "$addedCount new recipe(s) imported successfully. <a href='admin.php'>Back to Admin</a>";
} else {
    echo "No file uploaded.";
}
?>
