<?php
include('db.php');

// UPDATE block
if (isset($_POST['update']) && isset($_POST['id'])) {
    $ids = $_POST['id'];
    $titles = $_POST['title'];
    $categories = $_POST['category'];
    $prep_times = $_POST['prep_time'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $images = $_POST['image'];

    for ($i = 0; $i < count($ids); $i++) {
        $id = $conn->real_escape_string($ids[$i]);
        $title = $conn->real_escape_string($titles[$i]);
        $category = $conn->real_escape_string($categories[$i]);
        $prep_time = $conn->real_escape_string($prep_times[$i]);
        $ingredient = $conn->real_escape_string($ingredients[$i]);
        $instruction = $conn->real_escape_string($instructions[$i]);
        $image = $conn->real_escape_string($images[$i]);

        $conn->query("UPDATE recipes SET 
          title='$title',
          category='$category',
          prep_time='$prep_time',
          ingredients='$ingredient',
          instructions='$instruction',
          image='$image'
          WHERE id='$id'");
    }
}

// DELETE block
if (isset($_POST['delete_all'])) {
    $conn->query("DELETE FROM recipes");
} elseif (isset($_POST['delete']) && !empty($_POST['recipe_ids'])) {
    $ids = array_map('intval', $_POST['recipe_ids']);
    $idList = implode(',', $ids);
    $conn->query("DELETE FROM recipes WHERE id IN ($idList)");
}

// REBUILD XML
$result = $conn->query("SELECT * FROM recipes");
$xml = new SimpleXMLElement('<recipes/>');

while ($row = $result->fetch_assoc()) {
    $recipe = $xml->addChild('recipe');
    $recipe->addAttribute('id', $row['id']);
    $recipe->addChild('title', htmlspecialchars($row['title']));
    $recipe->addChild('category', htmlspecialchars($row['category']));
    $recipe->addChild('prepTime', htmlspecialchars($row['prep_time']));

    $ingredients = $recipe->addChild('ingredients');
    foreach (explode(';', $row['ingredients']) as $item) {
        $ingredients->addChild('item', htmlspecialchars(trim($item)));
    }

    $instructions = $recipe->addChild('instructions');
    foreach (explode(';', $row['instructions']) as $step) {
        $instructions->addChild('step', htmlspecialchars(trim($step)));
    }

    $recipe->addChild('image', htmlspecialchars($row['image']));
}

$xml->asXML('data/recipes.xml');

// Redirect back
header("Location: admin.php?status=done");
exit;
