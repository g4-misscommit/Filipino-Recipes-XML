<?php
include('db.php');
$result = $conn->query("SELECT * FROM recipes ORDER BY id DESC");

// Get the search term if it exists
$searchTerm = $_GET['q'] ?? '';

// Prepare and execute search query
if ($searchTerm) {
  $stmt = $conn->prepare("SELECT * FROM recipes WHERE title LIKE CONCAT('%', ?, '%')");
  $stmt->bind_param("s", $searchTerm);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  // If no search term, just fetch all recipes
  $result = $conn->query("SELECT * FROM recipes");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Recipes - SimplyTaste</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary-color: #6b3f2a;
      --accent-color: #c69874;
      --light-gray: #f5f5f5;
      --text-dark: #000000;
      --hover-shade: #f0e9d4;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background:rgb(240, 233, 212);
      color: var(--text-dark);
      padding-top: 60px; /* reduced from 70px */
    }

    .navbar {
      background-color: white !important;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      padding: 0.5rem 1.6rem;
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
      margin-right: 20px;
    }

    .navbar-brand span:first-child {
      color: rgb(49,30,1);
    }

    .navbar-brand span:last-child {
      color: var(--accent-color);
    }

    .navbar-nav {
      font-weight: bold;
      gap: 20px;
    }

    .navbar-nav .nav-link {
      color: black !important;
      font-weight: 700;
      padding: 10px 16px;
      transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
      transform: translateY(-3px);
      box-shadow: 5 10px 18px rgba(0,0,0,0.1);
      background-color: var(--accent-color);
      color: white !important;
      border-radius: 8px;
    }

    .search-bar input {
      border-radius: 8px;
    }
    #heading2 {
      color: rgb(49,30,1);
    }

    .container {
      max-width: 1100px;
      padding-top: 30px; /* reduced padding */
      padding-bottom: 50px;
    }

    .card-container {
      border-radius: 30px;
      padding: 20px;
      height: 100%;
    }

    .card {
      position: relative;
      background-color: #fff;
      border: 2px solid var(--primary-color);
      border-radius: 25px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 6px 10px rgba(0, 0, 0, 0.05);
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 5 10px 18px rgba(0,0,0,0.1);
    }

    .card .header {
      height: 250px;
      overflow: hidden;
    }

    .card .header img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .card .text {
      padding: 15px;
    }

    .card .text h2 {
      font-size: 1.50rem;
      font-weight: bold;
      margin-bottom: 8px;
      color: var(--primary-color);
    }

    .card .text i {
      margin-right: 8px;
      color: #777;
      font-size: 0.75rem;
    }

    .card .info {
      font-size: 0.95rem;
      color: #555;
      margin-top: 8px;
    }

    .card .btn {
      background-color:rgb(49,30,1);
      color: white;
      text-align: center;
      padding: 10px;
      text-transform: uppercase;
      text-decoration: none;
      transition: background 0.3s, color 0.3s;
      border-top: 1px solid #43632f;
      border-radius: 0;
    }

    .card .btn:hover {
      background-color: #f0e9d4;
      color: rgb(49,30,1);
      
    }

    .menu-heading {
      font-size: 2.5rem;
      font-weight: bold;
      color: var(--accent-color);
      margin-top: 10px; /* reduced */
    }

    h3.section-subheading {
      font-weight: bold;
      margin-bottom: 30px;
      color: var(--primary-color);
    }

    @media (max-width: 576px) {
      .navbar .form-control {
        margin-top: 10px;
        width: 100%;
      }
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
      <form class="d-flex" role="search" method="GET" action="recipes.php">
        <input class="form-control me-2" type="search" name="q" placeholder="Search" aria-label="Search" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>
      </form>
    </div>
  </div>
</nav>
<!-- Recipe Grid -->
<div class="container">
  <?php if ($searchTerm): ?>
    <h4>Search results for "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>"</h4>
  <?php endif; ?>

  <h2 id ="heading2" class="text-center menu-heading">Our Recipes</h2>
  <h3 class="text-center section-subheading">Simple. Delicious. Filipino.</h3>

  <div class="row g-4">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($recipe = $result->fetch_assoc()): ?>
        <div class="col-lg-4 col-md-6">
          <div class="card-container">
            <div class="card h-100">
              <div class="header">
                <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['title'] ?? 'Filipino Recipe'); ?>">
              </div>
              <div class="text">
                <h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
                <i class="fa fa-clock"> <?php echo htmlspecialchars($recipe['prep_time']); ?></i>
                <p class="info">Explore this delicious Filipino recipe and bring joy to your table.</p>
              </div>
              <a href="recipe_detail.php?id=<?php echo $recipe['id']; ?>" class="btn">Let's Cook!</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">No recipes found<?php echo $searchTerm ? ' for "' . htmlspecialchars($searchTerm) . '"' : ''; ?>.</p>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
