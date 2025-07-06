<?php
if (isset($_GET['recipe'])) {
    $recipeId = $_GET['recipe'];

    $xml = new DOMDocument;
    $xml->load('data/featured_recipes.xml');

    $recipes = $xml->getElementsByTagName('recipe');
    $found = false;

    foreach ($recipes as $recipe) {
        if ($recipe->getAttribute('id') === $recipeId) {
            $found = true;
            $title = $recipe->getElementsByTagName('title')->item(0)->nodeValue;
            $category = $recipe->getElementsByTagName('category')->item(0)->nodeValue;
            $prepTime = $recipe->getElementsByTagName('prepTime')->item(0)->nodeValue;
            $image = $recipe->getElementsByTagName('image')->item(0)->nodeValue;

            $ingredients = $recipe->getElementsByTagName('ingredients')->item(0)->getElementsByTagName('item');
            $steps = $recipe->getElementsByTagName('instructions')->item(0)->getElementsByTagName('step');

            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='recipeModalLabel'>" . htmlspecialchars($title) . "</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo "</div>";

            echo "<div class='modal-body'>";
            echo "<img src='" . htmlspecialchars($image) . "' alt='" . htmlspecialchars($title) . "' class='img-fluid mb-3' />";
            echo "<p><strong>Category:</strong> " . htmlspecialchars($category) . "</p>";
            echo "<p><strong>Preparation Time:</strong> " . htmlspecialchars($prepTime) . "</p>";

            echo "<h6>Ingredients:</h6>";
            echo "<ul>";
            foreach ($ingredients as $ingredient) {
                echo "<li>" . htmlspecialchars($ingredient->nodeValue) . "</li>";
            }
            echo "</ul>";

            echo "<h6>Steps:</h6>";
            echo "<ol>";
            foreach ($steps as $step) {
                echo "<li>" . htmlspecialchars($step->nodeValue) . "</li>";
            }
            echo "</ol>";
            echo "</div>";

            break;
        }
    }

    if (!$found) {
        echo "<p>Recipe not found.</p>";
    }
}
?>
