<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imported_file'])) {
    $fileTmpPath = $_FILES['imported_file']['tmp_name'];
    $fileName = $_FILES['imported_file']['name'];
    $fileSize = $_FILES['imported_file']['size'];
    $fileType = $_FILES['imported_file']['type'];

    // Check file type (optional but recommended)
    if ($fileType != 'text/xml' && $fileType != 'application/xml') {
        die("Error: Please upload a valid XML file.");
    }

    // Move uploaded file to replace recipes.xml
    if (move_uploaded_file($fileTmpPath, 'recipes.xml')) {
        echo "Recipes successfully imported! <a href='admin.php'>Back to Admin</a>";
    } else {
        echo "Error: Failed to upload file.";
    }
} else {
    echo "No file uploaded.";
}
?>
