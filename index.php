<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Recipe Website</title>
  <meta name="description" content="A delicious recipe website">
  <meta name="keywords" content="recipes, cooking, food">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

  <header class="bg-light py-3">
    <div class="container">
      <h1 class="text-center">Recipe Website</h1>
      <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#home">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#about">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#menu">Menu</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <main>

    <!-- Home Section -->
    <section id="home" class="py-5">
      <div class="container">
        <h2 class="text-center">Welcome to Our Recipe Website</h2>
        <p class="text-center">Discover delicious recipes and share your culinary creations!</p>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
      <div class="container">
        <h2 class="text-center">About Us</h2>
        <p class="text-center">We are passionate about cooking and sharing recipes with the world.</p>
      </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-5">
      <div class="container">
        <h2 class="text-center">Our Menu</h2>
        <div class="row">
          <div class="col-md-4">
            <h4>Starters</h4>
            <p>Delicious appetizers to start your meal.</p>
          </div>
          <div class="col-md-4">
            <h4>Main Courses</h4>
            <p>Hearty dishes that will satisfy your hunger.</p>
          </div>
          <div class="col-md-4">
            <h4>Desserts</h4>
            <p>Sweet treats to end your meal on a high note.</p>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Vendor JS Files -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>

</html>