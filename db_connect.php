<?php

// Database connection setup
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "root"; // Replace with your database password
$dbname = "seaart_prompts";       // Replace with your database name

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}