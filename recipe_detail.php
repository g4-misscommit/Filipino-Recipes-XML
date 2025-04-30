<?php
$xml = simplexml_load_file('recipes.xml');

// Get the recipe ID from the query string
$recipeId = $_GET['id'] ?? null;
$selectedRecipe = null;

// Search for the recipe by ID
if ($recipeId) {
  foreach ($xml->recipe as $recipe) {
    if ((string)$recipe['id'] === $recipeId) {
      $selectedRecipe = $recipe;
      break;
    }
  }
}

if (!$selectedRecipe) {
  echo "<h2>Recipe not found.</h2>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($selectedRecipe->title); ?> - SimplyTaste</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #fffaf5;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 70px;
    }

    .navbar {
      background-color: #a0522d;
    }

    .navbar-brand, .nav-link {
      color: white !important;
      font-weight: bold;
    }

    .recipe-title {
      font-weight: bold;
      font-size: 28px;
      margin-bottom: 10px;
    }

    .recipe-description {
      margin-bottom: 20px;
      color: #555;
      font-size: 18px;
    }

    .recipe-image {
      width: 100%;
      max-width: 600px;
      height: 100%;
      background-color: #ddd;
      display: block;
      margin: 20px auto;
      border-radius: 10px;
    }

    .section-title {
      font-weight: bold;
      margin-top: 30px;
      margin-bottom: 10px;
      font-size: 22px;
    }

    ul, ol {
      padding-left: 20px;
      font-size: 16px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.html">SimplyTaste</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<div class="container mt-4">
  <img src="<?php echo htmlspecialchars($selectedRecipe->image); ?>" alt="<?php echo htmlspecialchars($selectedRecipe->title); ?>" class="recipe-image">
  <h1 class="recipe-title"><?php echo htmlspecialchars($selectedRecipe->title); ?> Recipe</h1>

  <p class="recipe-description">
    Category: <?php echo htmlspecialchars($selectedRecipe->category); ?>
  </p>

  <div class="section-title">Ingredients</div>
  <ul>
    <?php foreach ($selectedRecipe->ingredients->item as $ingredient): ?>
      <li><?php echo htmlspecialchars($ingredient); ?></li>
    <?php endforeach; ?>
  </ul>

  <div class="section-title">Directions</div>
  <ol>
    <?php foreach ($selectedRecipe->instructions->step as $step): ?>
      <li><?php echo htmlspecialchars($step); ?></li>
    <?php endforeach; ?>
  </ol>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
