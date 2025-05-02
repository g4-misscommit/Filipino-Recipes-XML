<?php
include('db.php');

$recipeId = $_GET['id'] ?? null;
$selectedRecipe = null;

if ($recipeId) {
  $stmt = $conn->prepare("SELECT * FROM recipes WHERE id = ?");
  $stmt->bind_param("i", $recipeId);
  $stmt->execute();
  $result = $stmt->get_result();
  $selectedRecipe = $result->fetch_assoc();
}

if (!$selectedRecipe) {
  echo "<h2>Recipe not found.</h2>";
  exit;
}

// Convert ingredients and instructions back to arrays
$ingredients = explode(';', $selectedRecipe['ingredients']);
$instructions = explode(';', $selectedRecipe['instructions']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($selectedRecipe['title']); ?> - SimplyTaste</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">

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
      height: auto;
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

<!-- Back to Recipes button -->
<div class="container mt-4">
  <a href="recipes.php" class="btn btn-outline-secondary mb-3">
    <i class="bi bi-arrow-left-circle"></i> Back to Recipes
  </a>

<div class="container mt-4">
  <img src="<?php echo htmlspecialchars($selectedRecipe['image']); ?>" alt="<?php echo htmlspecialchars($selectedRecipe['title']); ?>" class="recipe-image">
  <h1 class="recipe-title"><?php echo htmlspecialchars($selectedRecipe['title']); ?> Recipe</h1>

  <p class="recipe-description">
    Category: <?php echo htmlspecialchars($selectedRecipe['category']); ?><br>
    Prep Time: <?php echo htmlspecialchars($selectedRecipe['prep_time']); ?>
  </p>

  <div class="section-title">Ingredients</div>
  <ul>
    <?php foreach ($ingredients as $ingredient): ?>
      <li><?php echo htmlspecialchars(trim($ingredient)); ?></li>
    <?php endforeach; ?>
  </ul>

  <div class="section-title">Directions</div>
  <ol>
    <?php foreach ($instructions as $step): ?>
      <li><?php echo htmlspecialchars(trim($step)); ?></li>
    <?php endforeach; ?>
  </ol>

  <div class="section-title mt-5">You'll Also Love</div>
  <div class="row g-4 justify-content-center">
    <?php
    // Fetch 4 random recipes in the same category, excluding the current one
    $category = $selectedRecipe['category'];
    $stmt = $conn->prepare("SELECT id, title, image FROM recipes WHERE category = ? AND id != ? ORDER BY RAND() LIMIT 4");
    $stmt->bind_param("si", $category, $recipeId);
    $stmt->execute();
    $related = $stmt->get_result();

    while ($r = $related->fetch_assoc()):
    ?>
      <div class="col-6 col-md-3">
        <div class="card text-center">
          <a href="recipe_detail.php?id=<?php echo $r['id']; ?>">
            <img src="<?php echo htmlspecialchars($r['image']); ?>" alt="<?php echo htmlspecialchars($r['title']); ?>" class="img-fluid rounded mb-2" style="height: 130px; object-fit: cover;">
            <div class="fw-bold text-dark"><?php echo htmlspecialchars($r['title']); ?></div>
          </a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

</div>

 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
