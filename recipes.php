<?php
include('db.php');

// Fetch all recipes from the database
$result = $conn->query("SELECT * FROM recipes ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recipes - SimplyTaste</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

    .search-bar {
      text-align: right;
      margin: 20px 0;
    }

    .card {
      position: relative;
      background: #fff;
      box-shadow: 0px 0px 20px rgba(0,0,0,0.1);
      border-radius: 12px;
      overflow: hidden;
      transition: transform 0.2s ease-in-out;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .card .header {
      height: 350px;
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

    .card .text h1 {
      font-size: 1.2rem;
      text-transform: uppercase;
      margin-bottom: 10px;
    }

    .card .text i {
      margin-right: 10px;
      color: #999;
      font-size: 0.9rem;
    }

    .card .info {
      font-size: 0.9rem;
      color: #555;
      margin-top: 10px;
    }

    .card .btn {
      display: block;
      background-color: #ef3e36;
      color: white;
      text-align: center;
      padding: 10px;
      text-transform: uppercase;
      text-decoration: none;
      font-size: 0.9rem;
      transition: background 0.3s;
    }

    .card .btn:hover {
      background-color: #17bebb;
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
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>
      </form>
    </div>
  </div>
</nav>

<!-- Recipe Grid -->
<div class="container mt-4">
  <div class="row g-4">
    <?php while ($recipe = $result->fetch_assoc()): ?>
      <div class="col-md-4 col-sm-6">
        <div class="card">
          <div class="header">
            <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
          </div>
          <div class="text">
            <h1 class="food"><?php echo htmlspecialchars($recipe['title']); ?></h1>
            <i class="fa fa-clock"> <?php echo htmlspecialchars($recipe['prep_time']); ?></i>
            <p class="info">Explore this delicious Filipino recipe and bring joy to your table.</p>
          </div>
          <a href="recipe_detail.php?id=<?php echo $recipe['id']; ?>" class="btn">Let's Cook!</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
