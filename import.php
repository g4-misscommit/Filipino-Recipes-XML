<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imported_file'])) {
    $fileTmpPath = $_FILES['imported_file']['tmp_name'];
    $fileType = $_FILES['imported_file']['type'];

    // Validate file type
    if ($fileType != 'text/xml' && $fileType != 'application/xml') {
        die("Error: Please upload a valid XML file.");
    }

    $importedXml = simplexml_load_file($fileTmpPath);
    if (!$importedXml || !isset($importedXml->recipe)) {
        die("Error: Invalid XML structure.");
    }

    $addedCount = 0;

    foreach ($importedXml->recipe as $newRecipe) {
        if (
            !isset($newRecipe->title) ||
            !isset($newRecipe->category) ||
            !isset($newRecipe->prepTime) ||
            !isset($newRecipe->ingredients) ||
            !isset($newRecipe->instructions) ||
            !isset($newRecipe->image)
        ) {
            continue;
        }

        $titleCheck = $conn->real_escape_string((string)$newRecipe->title);
        $checkQuery = $conn->query("SELECT id FROM recipes WHERE title = '$titleCheck' LIMIT 1");
        if ($checkQuery->num_rows > 0) {
            continue;
        }

        $title = (string)$newRecipe->title;
        $category = (string)$newRecipe->category;
        $prepTime = (string)$newRecipe->prepTime;
        $image = (string)$newRecipe->image;

        $ingredientsArray = [];
        foreach ($newRecipe->ingredients->item as $item) {
            $ingredientsArray[] = trim($item);
        }

        $instructionsArray = [];
        foreach ($newRecipe->instructions->step as $step) {
            $instructionsArray[] = trim($step);
        }

        $ingredientsStr = implode('; ', $ingredientsArray);
        $instructionsStr = implode('; ', $instructionsArray);

        $stmt = $conn->prepare("INSERT INTO recipes (title, category, prep_time, ingredients, instructions, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $title, $category, $prepTime, $ingredientsStr, $instructionsStr, $image);
        $stmt->execute();

        $addedCount++;
    }

    echo "$addedCount new recipe(s) imported successfully. <a href='admin.php'>Back to Admin</a>";
} else {
    echo "No file uploaded.";
}
