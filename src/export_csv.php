<?php
include('db.php');

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="recipes_export.csv"');

$output = fopen("php://output", "w");

// CSV headers
fputcsv($output, ['ID', 'Title', 'Category', 'Prep Time', 'Ingredients', 'Instructions', 'Image']);

// Fetch and write rows
$result = $conn->query("SELECT * FROM recipes");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['title'],
        $row['category'],
        $row['prep_time'],
        $row['ingredients'],
        $row['instructions'],
        $row['image']
    ]);
}

fclose($output);
exit;
?>
