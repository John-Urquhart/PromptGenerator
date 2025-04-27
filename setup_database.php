<?php
require_once 'db_connect.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Array to store results
$results = [];

// Function to safely execute SQL
function executeSql($conn, $sql, $description) {
    try {
        if ($conn->query($sql)) {
            return " Success: $description";
        } else {
            return " Error: $description - " . $conn->error;
        }
    } catch (mysqli_sql_exception $e) {
        // For duplicate index errors, treat as success
        if ($e->getCode() == 1061) { // Error code for duplicate key name
            return " Success: $description (already exists)";
        }
        return " Error: $description - " . $e->getMessage();
    }
}

// Create tables if they don't exist
$tables = [
    "treatments" => "
        CREATE TABLE IF NOT EXISTS treatments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            treatment_text VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
    
    "art_styles" => "
        CREATE TABLE IF NOT EXISTS art_styles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            style_name VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
    
    "animal_characters" => "
        CREATE TABLE IF NOT EXISTS animal_characters (
            id INT AUTO_INCREMENT PRIMARY KEY,
            character_name VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
    
    "poses" => "
        CREATE TABLE IF NOT EXISTS poses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pose_name VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
    
    "catchphrases" => "
        CREATE TABLE IF NOT EXISTS catchphrases (
            id INT AUTO_INCREMENT PRIMARY KEY,
            catchphrase_text VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
    
    "humour_styles" => "
        CREATE TABLE IF NOT EXISTS humour_styles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            humour_name VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
];

// Create each table
foreach ($tables as $table => $sql) {
    $results[] = executeSql($conn, $sql, "Created $table table");
}

// Add indexes for performance (without IF NOT EXISTS)
$indexes = [
    "CREATE INDEX idx_treatment_text ON treatments(treatment_text)",
    "CREATE INDEX idx_style_name ON art_styles(style_name)",
    "CREATE INDEX idx_character_name ON animal_characters(character_name)",
    "CREATE INDEX idx_pose_name ON poses(pose_name)",
    "CREATE INDEX idx_catchphrase_text ON catchphrases(catchphrase_text)",
    "CREATE INDEX idx_humour_name ON humour_styles(humour_name)"
];

foreach ($indexes as $sql) {
    $results[] = executeSql($conn, $sql, "Created index");
}

// Output results in a nice format
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .success { color: green; }
        .error { color: red; }
        .result { margin: 0.5rem 0; padding: 0.5rem; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>
    <h1>Database Setup Results</h1>
    <?php foreach ($results as $result): ?>
        <div class="result <?php echo strpos($result, ' Success') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($result); ?>
        </div>
    <?php endforeach; ?>
    
    <h2>Next Steps</h2>
    <ul>
        <li>Use the <a href="admin/upload_csv_form.php">CSV Upload Form</a> to populate the tables</li>
        <li>Or use phpMyAdmin to manually insert data</li>
        <li>Test the dropdowns on the <a href="index.html">main page</a></li>
        <li><a href="admin/admin_panel.php">Return to Admin Panel</a></li>
    </ul>
</body>
</html>
<?php
$conn->close();
?>