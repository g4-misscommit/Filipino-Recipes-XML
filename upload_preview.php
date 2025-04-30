<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xml_file'])) {
    $tmp = $_FILES['xml_file']['tmp_name'];
    $type = $_FILES['xml_file']['type'];

    if ($type != 'text/xml' && $type != 'application/xml') {
        http_response_code(400);
        echo "Invalid file.";
        exit;
    }

    move_uploaded_file($tmp, 'tmp_preview.xml');
    http_response_code(200); // Let JS know it's OK
} else {
    http_response_code(400);
    echo "No file uploaded.";
}
