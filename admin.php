<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

include('db.php');
$errorMessage = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - SimplyTaste</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">

  <style>
    body {
      background-color: white;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 70px;
    }

    .navbar {
      background-color: white !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
    }

    .navbar-brand span.simply {
      color: black;
    }

    .navbar-brand span.taste {
      color: #ffbd59;
    }

    .navbar-brand span.admin {
      color: black;
      font-weight: bold;
      font-size: 2rem;
      margin-left: 8px;
    }

    .btn-logout {
      background-color: black;
      color: white;
      border: none;
    }

    .btn-logout:hover {
      background-color: #ffbd59;
      color: white;
    }

    .btn-preview {
      background-color: black;
      color: white;
      border: none;
    }

    .btn-preview:hover {
      background-color: #ffbd59;
      color: white;
    }


    .section-title {
      font-weight: bold;
      font-size: 24px;
      margin-bottom: 20px;
    }

    .form-section,
    .export-section,
    .import-section {
      background: #f5f5f5;
      padding: 20px;
      margin-bottom: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .btn-custom {
      background-color: #c9a18b;
      color: #3d1d0c;
      border: none;
    }

    .btn-custom:hover {
      background-color: #b98c77;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.html">
      <span class="simply">Simply</span><span class="taste">Taste</span>
      <span class="admin">Admin</span>
    </a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-logout ms-2">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="container my-5">

  <!-- Add New Recipe Section -->
  <div class="form-section">
    <h2 class="section-title">Add a New Recipe</h2>
    <form action="add_recipe.php" method="POST" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Title:</label>
          <input type="text" class="form-control" name="title" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Category:</label>
          <select class="form-select" name="category" required>
            <option value="">Select Category</option>
            <option value="Breakfast">Breakfast</option>
            <option value="Lunch">Lunch</option>
            <option value="Dinner">Dinner</option>
            <option value="Merienda">Merienda</option>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Preparation Time:</label>
          <input type="text" class="form-control" name="prepTime" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Upload Recipe Image:</label>
          <div id="drop-area" class="border-dashed text-center" onclick="document.getElementById('image-input').click();">
            <input type="file" id="image-input" name="image" accept="image/*" onchange="previewImage(event)" hidden required>
            <div id="drop-message">
              <i class="bi bi-upload" style="font-size: 2rem; color: #999;"></i>
              <p class="text-muted">Click or drag image here</p>
            </div>
            <div class="image-wrapper mt-2 position-relative" style="display:none;">
              <img id="image-preview" class="img-fluid rounded" alt="Preview" />
              <button type="button" class="btn-close position-absolute top-0 end-0 m-1" aria-label="Close" onclick="removePreview()"></button>
            </div>
          </div>
        </div>
      </div>

      <!-- Dynamic Ingredients -->
      <div class="mb-3">
        <label class="form-label">Ingredients:</label>
        <div id="ingredients-list">
          <input type="text" class="form-control mb-2" name="ingredients[]" placeholder="Enter an ingredient" required>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addIngredient()"><i class="bi bi-plus"></i> Add Ingredient</button>
      </div>

      <!-- Dynamic Instructions -->
      <div class="mb-3">
        <label class="form-label">Instructions:</label>
        <div id="instructions-list">
          <input type="text" class="form-control mb-2" name="instructions[]" placeholder="Enter a step" required>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addInstruction()"><i class="bi bi-plus"></i> Add Step</button>
      </div>

      <button type="submit" class="btn btn-custom">Save Recipe</button>
    </form>
  </div>

  <!-- Manage Recipes Section -->
  <div class="form-section">
    <h2 class="section-title">Manage Existing Recipes</h2>
    <form method="POST" action="delete_recipe.php" onsubmit="return confirm('Are you sure you want to delete the selected recipe(s)?');">
      <div class="mb-3">
        <button type="submit" class="btn btn-danger btn-sm">Delete Selected</button>
        <button type="submit" name="delete_all" value="1" class="btn btn-warning btn-sm" onclick="return confirm('Delete ALL recipes? This cannot be undone.')">Delete All</button>
      </div>
      <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-striped table-bordered mb-0">
          <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
            <tr>
              <th><input type="checkbox" id="selectAll"></th>
              <th>ID</th>
              <th>Title</th>
              <th>Category</th>
              <th>Prep Time</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $result = $conn->query("SELECT * FROM recipes ORDER BY id DESC");
              while ($row = $result->fetch_assoc()):
            ?>
              <tr>
                <td><input type="checkbox" name="recipe_ids[]" value="<?php echo $row['id']; ?>"></td>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['prep_time']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </form>
  </div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="errorModalLabel">Error</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="errorModalBody">
        <!-- Error message will be inserted here -->
      </div>
    </div>
  </div>
</div>

<!-- JavaScript to handle select all -->
<script>
  document.getElementById('selectAll').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[name="recipe_ids[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
  });
</script>

  <!-- Export Recipes Section -->
  <div class="export-section text-center">
  <h2 class="section-title">Export Recipes</h2>
  <a href="export.php" class="btn btn-success m-2">Export as XML</a>
  <a href="export_csv.php" class="btn btn-warning m-2">Export as CSV</a>
</div>

<!-- Import Recipes Section -->
<div class="import-section">
  <h2 class="section-title">Import Recipes</h2>
  <form id="uploadForm" enctype="multipart/form-data">
    <label for="xml_file" class="form-label">Upload Recipe XML</label>
    <input type="file" name="xml_file" id="xml_file" class="form-control" accept=".xml" required>
    <button type="submit" class="btn btn-preview mt-2">Preview XML</button>
    </form>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Recipe Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <iframe id="previewFrame" src="" width="100%" height="500" style="border:none;"></iframe>
      </div>
      <div class="modal-footer">
        <form method="POST" action="import.php">
          <input type="hidden" name="final_import" value="1">
          <button type="submit" class="btn btn-success">Import to Database</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">Import Successful</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="successMessage"></p>
      </div>
    </div>
  </div>
</div>

<script>
function addIngredient() {
  const ingredientsList = document.getElementById('ingredients-list');
  const input = document.createElement('input');
  input.type = 'text';
  input.name = 'ingredients[]';
  input.className = 'form-control mb-2';
  input.placeholder = 'Enter an ingredient';
  ingredientsList.appendChild(input);
}

function addInstruction() {
  const instructionsList = document.getElementById('instructions-list');
  const input = document.createElement('input');
  input.type = 'text';
  input.name = 'instructions[]';
  input.className = 'form-control mb-2';
  input.placeholder = 'Enter a step';
  instructionsList.appendChild(input);
}
</script>
<script>
const dropArea = document.getElementById('drop-area');
const input = document.getElementById('image-input');

['dragenter', 'dragover'].forEach(eventName => {
  dropArea.addEventListener(eventName, (e) => {
    e.preventDefault();
    e.stopPropagation();
    dropArea.classList.add('dragover');
  });
});

['dragleave', 'drop'].forEach(eventName => {
  dropArea.addEventListener(eventName, (e) => {
    e.preventDefault();
    e.stopPropagation();
    dropArea.classList.remove('dragover');
  });
});

dropArea.addEventListener('drop', (e) => {
  const files = e.dataTransfer.files;
  if (files.length > 0) {
    input.files = files; // set input value
    previewImage({ target: input });
  }
});

function previewImage(event) {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = function () {
    const preview = document.getElementById('image-preview');
    preview.src = reader.result;
    preview.style.display = 'block';
    document.querySelector('.image-wrapper').style.display = 'block';
    document.getElementById('drop-message').style.display = 'none';
  };
  reader.readAsDataURL(file);
}

function removePreview() {
  input.value = "";
  document.getElementById('image-preview').src = "#";
  document.getElementById('image-preview').style.display = "none";
  document.querySelector('.image-wrapper').style.display = 'none';
  document.getElementById('drop-message').style.display = 'flex';
}
</script>

<script>
  const uploadForm = document.getElementById('uploadForm');

  uploadForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    const formData = new FormData(uploadForm);

    const response = await fetch('upload_preview.php', {
      method: 'POST',
      body: formData
    });

    if (response.ok) {
      document.getElementById('previewFrame').src = 'preview.php';
      new bootstrap.Modal(document.getElementById('previewModal')).show();
    } else {
      alert('Failed to upload XML for preview.');
    }
  });
</script>

<script>
  // Check URL for import_success parameter
  const urlParams = new URLSearchParams(window.location.search);
  const importCount = urlParams.get('import_success');

  if (importCount) {
    document.getElementById('successMessage').innerText =
      `${importCount} new recipe(s) imported successfully.`;
    new bootstrap.Modal(document.getElementById('successModal')).show();

    // Clean up the URL to avoid repeat modals on reload
    window.history.replaceState({}, document.title, window.location.pathname);
  }
</script>

<!-- Error Handling script -->
<script>
  const errorMessage = <?php echo json_encode($errorMessage); ?>;
  if (errorMessage) {
    document.getElementById('errorModalBody').textContent = errorMessage;
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if ($errorMessage): ?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    document.getElementById('errorModalBody').textContent = <?php echo json_encode($errorMessage); ?>;
    errorModal.show();
  });
</script>
<?php endif; ?>
</body>
</html>

