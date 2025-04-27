<?php
// get_list.php

require_once 'db_connect.php';

// Get table and column from query string
$table = $_GET['table'] ?? '';
$column = $_GET['column'] ?? '';

// Basic security check: allow only known tables and columns
$allowed = [
    'poses' => 'pose_name',
    'animal_characters' => 'character_name',
    'art_styles' => 'style_name',
    'humour_styles' => 'humour_name',
    'catchphrases' => 'catchphrase_text',
    'treatments' => 'treatment_text'
];

if (!isset($allowed[$table]) || $allowed[$table] !== $column) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid request."]);
    exit;
}

// Safe query
$sql = "SELECT `$column` FROM `$table` ORDER BY `$column` ASC";
$result = $conn->query($sql);

// Prepare output
$items = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = [
            "value" => $row[$column],
            "label" => $row[$column]
        ];
    }
}

$conn->close();

// Return JSON
header('Content-Type: application/json');
echo json_encode($items);
?>
