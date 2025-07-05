<?php 
session_start();
include('db.php');

$recipeId = $_GET['id'] ?? null;
$selectedRecipe = null;

// Handle review form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($recipeId && $name && $email && $rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO reviews (recipe_id, name, email, rating, comment) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issis", $recipeId, $name, $email, $rating, $comment);
        $stmt->execute();

        // Store user info in session to avoid asking again
        $_SESSION['reviewer_name'] = $name;
        $_SESSION['reviewer_email'] = $email;

        // Redirect to avoid form resubmission
        header("Location: recipe_detail.php?id=" . $recipeId);
        exit;
    }
}

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

$ingredients = explode(';', $selectedRecipe['ingredients']);
$instructions = explode(';', $selectedRecipe['instructions']);

// Fetch reviews for this recipe
$reviews = [];
$stmt = $conn->prepare("SELECT name, rating, comment, created_at FROM reviews WHERE recipe_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $recipeId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($selectedRecipe['title']); ?> - SimplyTaste</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #6b3f2a;
      --accent-color: #ffbd59;
      --text-color: #333;
      --hover-green: #43632f;
    }

    body {
      background-color: #fff;
      font-family: 'Segoe UI', sans-serif;
      color: var(--text-color);
      padding-top: 70px;
    }

    .navbar {
      background-color: white !important;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      padding: 0.5rem 1.6rem;
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
    }

    .navbar-brand span:first-child {
      color: black;
    }

    .navbar-brand span:last-child {
      color: var(--accent-color);
    }

    .navbar-nav .nav-link {
      color: black !important;
      font-weight: 700;
      margin-right: 20px;
      transition: background-color 0.3s;
    }

    .navbar-nav .nav-link:hover {
      background-color: var(--accent-color);
      color: white !important;
      border-radius: 8px;
    }

    .container {
      max-width: 900px;
    }

    .recipe-title {
      font-weight: bold;
      font-size: 2rem;
      margin-top: 20px;
      color: var(--primary-color);
    }

    .recipe-description {
      margin-bottom: 20px;
      font-size: 1rem;
      color: #666;
    }

    .recipe-image {
      width: auto;
      height: 100%;
      border-radius: 15px;
      object-fit: cover;
      margin-bottom: 20px;
    }

    .section-title {
      font-weight: bold;
      font-size: 1.3rem;
      color: var(--accent-color);
      margin-top: 30px;
    }

    ul, ol {
      padding-left: 20px;
      font-size: 1rem;
      color: var(--text-color);
    }

    .back-btn {
      margin-top: 20px;
      margin-bottom: 20px;
      font-weight: 600;
    }
    .related-section {
      margin-bottom: 50px;
    }

    .related-recipes .card {
      border: 2px solid var(--accent-color);
      border-radius: 10px;
      transition: transform 0.2s ease-in-out;
    }

    .related-recipes .card:hover {
      transform: scale(1.05);
    }

    .related-recipes img {
      border-radius: 10px 10px 0 0;
    }

    .related-recipes .fw-bold {
      padding: 8px;
      font-size: 0.95rem;
    }

    .section-subheading {
      font-size: 1.1rem;
      color: #555;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid ps-3">
    <a class="navbar-brand me-3" href="index.html">
      <span>Simply</span><span>Taste</span>
    </a>
    <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav me-3">
        <li class="nav-item"><a class="nav-link" href="index.html#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="index.html#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="index.html#menu">Menu</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
<a href="recipes.php" class="btn btn-outline-secondary back-btn">
    <i class="bi bi-arrow-left-circle"></i> Back to Recipes
  </a>
</div>

<!-- Recipe Detail -->
<div class="container mt-4">

  <h1 class="recipe-title"><?php echo htmlspecialchars($selectedRecipe['title']); ?> Recipe</h1>
  <p class="recipe-description">
    Category: <?php echo htmlspecialchars($selectedRecipe['category']); ?><br>
    Prep Time: <?php echo htmlspecialchars($selectedRecipe['prep_time']); ?>
  </p>

  <img src="<?php echo htmlspecialchars($selectedRecipe['image']); ?>" alt="<?php echo htmlspecialchars($selectedRecipe['title']); ?>" class="recipe-image">

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

    <!-- Print Button -->
    <button onclick="window.print()" class="btn btn-outline-primary mt-4 no-print">
    <i class="bi bi-printer"></i> Print Recipe
    </button>

    <div class="related-section">
      <div class="section-title mt-5">You'll Also Love</div>
      <div class="row g-4 related-recipes justify-content-center">
        <?php
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
                <img src="<?php echo htmlspecialchars($r['image']); ?>" alt="<?php echo htmlspecialchars($r['title']); ?>" class="img-fluid" style="height: 130px; object-fit: cover;">
                <div class="fw-bold text-dark"><?php echo htmlspecialchars($r['title']); ?></div>
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Reviews Section -->
<div class="container mt-5">
  <h2 class="section-title">Reviews</h2>

  <?php if (count($reviews) > 0): ?>
    <?php foreach ($reviews as $review): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($review['name']); ?> <small class="text-muted">rated <?php echo $review['rating']; ?>/5</small></h5>
          <p class="card-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
          <p class="card-text"><small class="text-muted"><?php echo date('F j, Y, g:i a', strtotime($review['created_at'])); ?></small></p>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No reviews yet. Be the first to review this recipe!</p>
  <?php endif; ?>

  <h3 class="mt-4">Add Your Review</h3>
  <form method="POST" action="recipe_detail.php?id=<?php echo $recipeId; ?>">
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($_SESSION['reviewer_name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($_SESSION['reviewer_email'] ?? ''); ?>">
    </div>
    <div class="mb-3">
      <label for="rating" class="form-label">Rating</label>
      <select class="form-select" id="rating" name="rating" required>
        <option value="">Select rating</option>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="comment" class="form-label">Comment</label>
      <textarea class="form-control" id="comment" name="comment" rows="4"></textarea>
    </div>
    <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
  </form>
</div>

</body>
</html>
