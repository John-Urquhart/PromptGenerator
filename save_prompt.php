<?php

require_once 'db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate incoming data
$treatment   = $conn->real_escape_string($data['treatment'] ?? '');
$artStyle    = $conn->real_escape_string($data['artStyle'] ?? '');
$animal_character = $conn->real_escape_string($data['animal_character'] ?? '');
$subcategory = $conn->real_escape_string($data['subcategory'] ?? '');
$pose        = $conn->real_escape_string($data['pose'] ?? '');
$catchphrase = $conn->real_escape_string($data['catchphrase'] ?? '');
$humour      = $conn->real_escape_string($data['humour'] ?? '');
$custom      = $conn->real_escape_string($data['custom'] ?? '');

// Insert into database
$sql = "INSERT INTO prompts (treatment, artStyle, animal_character, subcategory, pose, catchphrase, humour, custom)
        VALUES ('{$treatment}', '{$artStyle}', '{$animal_character}', '{$subcategory}', '{$pose}', '{$catchphrase}', '{$humour}', '{$custom}')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Prompt saved successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error saving prompt."]);
}

// Close connection
$conn->close();
?>