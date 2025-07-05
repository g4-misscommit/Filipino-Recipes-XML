<?php
include('db.php');

// Handle form submission to insert a new recipe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $category = $_POST['category'];
  $prepTime = $_POST['prepTime'];
  $ingredients = $_POST['ingredients'];
  $instructions = $_POST['instructions'];

  // Handle image upload
  $imagePath = '';
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = 'assets/images/';
    $fileName = basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
      $imagePath = $targetFile;
    } else {
      die("Error uploading image.");
    }
  } else {
    die("Image upload failed or missing.");
  }

  // Check for duplicate title in DB
  $escapedTitle = $conn->real_escape_string($title);
  $duplicateCheck = $conn->query("SELECT id FROM recipes WHERE title = '$escapedTitle' LIMIT 1");
  if ($duplicateCheck && $duplicateCheck->num_rows > 0) {
    header("Location: admin.php?error=duplicate");
    exit;
  }

  $ingredientsStr = implode('; ', $ingredients);
  $instructionsStr = implode('; ', $instructions);

  $stmt = $conn->prepare("INSERT INTO recipes (title, category, prep_time, ingredients, instructions, image) VALUES (?, ?, ?, ?, ?, ?)");
  if ($stmt) {
    $stmt->bind_param("ssssss", $title, $category, $prepTime, $ingredientsStr, $instructionsStr, $imagePath);
    $stmt->execute();

    // Load existing XML or create a new one
    $xml = file_exists('data/recipes.xml') ? simplexml_load_file('data/recipes.xml') : new SimpleXMLElement('<recipes/>');

    // Determine the highest existing ID
    $lastId = 0;
    foreach ($xml->recipe as $r) {
      $lastId = max($lastId, (int)$r['id']);
    }
    $newId = $lastId + 1;

    // Add new recipe to XML
    $recipe = $xml->addChild('recipe');
    $recipe->addAttribute('id', $newId);
    $recipe->addChild('title', htmlspecialchars($title));
    $recipe->addChild('category', htmlspecialchars($category));
    $recipe->addChild('prepTime', htmlspecialchars($prepTime));

    $ingredientsEl = $recipe->addChild('ingredients');
    foreach ($ingredients as $item) {
      $ingredientsEl->addChild('item', htmlspecialchars($item));
    }

    $instructionsEl = $recipe->addChild('instructions');
    foreach ($instructions as $step) {
      $instructionsEl->addChild('step', htmlspecialchars($step));
    }

    $recipe->addChild('image', htmlspecialchars($imagePath));

    $xml->asXML('data/recipes.xml');

    // Show success modal
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recipe Saved</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="modal fade show" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" style="display:block;" aria-modal="true" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
      </div>
      <div class="modal-body">
        Successfully added recipe to Database.
      </div>
      <div class="modal-footer">
        <a href="admin.php" class="btn btn-success">OK</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
    exit;
  } else {
    die("SQL Error: " . $conn->error);
  }
}
?>
