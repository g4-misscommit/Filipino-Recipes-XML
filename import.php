<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fileTmpPath = 'data/tmp_preview.xml';

    if (!file_exists($fileTmpPath)) {
        $error = urlencode("Preview file not found.");
        header("Location: admin.php?error=$error");
        exit;
    }
    
    $importedXml = simplexml_load_file($fileTmpPath);
    if (!$importedXml || !isset($importedXml->recipe)) {
        $error = urlencode("Invalid XML structure.");
        header("Location: admin.php?error=$error");
        exit;
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
            continue; // skip malformed recipes
        }

        $titleCheck = $conn->real_escape_string((string)$newRecipe->title);
        $checkQuery = $conn->query("SELECT id FROM recipes WHERE title = '$titleCheck' LIMIT 1");
        if ($checkQuery->num_rows > 0) {
            continue; // skip duplicates
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

    header("Location: admin.php?import_success=$addedCount");
    exit;
    } else {
    echo "No file uploaded.";
}
