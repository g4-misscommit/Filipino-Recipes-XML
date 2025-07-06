<?php
// Load the uploaded XML file
$xmlFile = 'data/tmp_preview.xml';

if (!file_exists($xmlFile)) {
    http_response_code(404);
    echo "<div class='container mt-5'><div class='alert alert-danger'>XML file not found.</div></div>";
    exit;
}

$xml = new DOMDocument();
$xml->load($xmlFile);
$recipes = $xml->getElementsByTagName('recipe');

echo "<div class='container mt-5'>";
echo "<h2 class='mb-4'>Preview of Uploaded Recipes</h2>";

foreach ($recipes as $recipe) {
    $title = $recipe->getElementsByTagName('title')->item(0)->nodeValue;
    $category = $recipe->getElementsByTagName('category')->item(0)->nodeValue;
    $prepTime = $recipe->getElementsByTagName('prepTime')->item(0)->nodeValue;
    $image = $recipe->getElementsByTagName('image')->item(0)->nodeValue;

    $ingredients = $recipe->getElementsByTagName('ingredients')->item(0)->getElementsByTagName('item');
    $instructions = $recipe->getElementsByTagName('instructions')->item(0)->getElementsByTagName('step');

    echo "<div class='card mb-4 shadow-sm'>";
    echo "<div class='card-body'>";
    echo "<h3 class='card-title'>" . htmlspecialchars($title) . "</h3>";

    if (!empty($image)) {
        echo "<img src='" . htmlspecialchars($image) . "' alt='" . htmlspecialchars($title) . "' class='img-fluid mb-3 rounded' style='max-width:200px;'>";
    }

    echo "<p class='mb-1'><strong>Category:</strong> " . htmlspecialchars($category) . "</p>";
    echo "<p class='mb-3'><strong>Preparation Time:</strong> " . htmlspecialchars($prepTime) . "</p>";

    echo "<h5>Ingredients</h5>";
    echo "<ul class='list-group mb-3'>";
    foreach ($ingredients as $ingredient) {
        echo "<li class='list-group-item'>" . htmlspecialchars($ingredient->nodeValue) . "</li>";
    }
    echo "</ul>";

    echo "<h5>Instructions</h5>";
    echo "<ol class='list-group list-group-numbered'>";
    foreach ($instructions as $step) {
        echo "<li class='list-group-item'>" . htmlspecialchars($step->nodeValue) . "</li>";
    }
    echo "</ol>";

    echo "</div></div>";
}

echo "</div>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recipe Preview</title>
  <link rel="stylesheet" href="preview.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

