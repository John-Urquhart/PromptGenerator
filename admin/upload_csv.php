<?php
session_start();
require_once '../db_connect.php';

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['upload_error'] = "Invalid request";
    header('Location: upload_csv_form.php');
    exit();
}

// Verify file upload
if (!isset($_FILES['csvFile']) || $_FILES['csvFile']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['upload_error'] = "File upload failed";
    header('Location: upload_csv_form.php');
    exit();
}

// Check file type
$mimeType = mime_content_type($_FILES['csvFile']['tmp_name']);
if ($mimeType !== 'text/csv' && $mimeType !== 'text/plain') {
    $_SESSION['upload_error'] = "Invalid file type. Please upload a CSV file.";
    header('Location: upload_csv_form.php');
    exit();
}

// Function to validate table name
function isValidTable($table) {
    $validTables = [
        'treatments' => 'treatment_text',
        'art_styles' => 'style_name',
        'animal_characters' => 'character_name',
        'poses' => 'pose_name',
        'catchphrases' => 'catchphrase_text',
        'humour_styles' => 'humour_name'
    ];
    return isset($validTables[$table]) ? $validTables[$table] : false;
}

// Start transaction
$conn->begin_transaction();

try {
    $file = fopen($_FILES['csvFile']['tmp_name'], 'r');
    if (!$file) {
        throw new Exception("Could not open CSV file");
    }

    // Skip header row
    $header = fgetcsv($file);
    if (!$header) {
        throw new Exception("Empty CSV file");
    }

    $uploadType = $_POST['upload_type'] ?? 'individual';
    $successCount = 0;
    $errors = [];

    if ($uploadType === 'combined') {
        // Process combined CSV format
        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 3) continue; // Skip invalid rows

            $table = trim($row[0]);
            $column = trim($row[1]);
            $value = trim($row[2]);

            // Validate table and column
            $expectedColumn = isValidTable($table);
            if (!$expectedColumn || $expectedColumn !== $column) {
                $errors[] = "Invalid table or column: $table.$column";
                continue;
            }

            // Insert data
            $stmt = $conn->prepare("INSERT IGNORE INTO `$table` (`$column`) VALUES (?)");
            $stmt->bind_param('s', $value);
            if ($stmt->execute()) {
                $successCount++;
            } else {
                $errors[] = "Error inserting into $table: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        // Process individual table CSV
        $table = $_POST['table'] ?? '';
        $column = isValidTable($table);
        
        if (!$column) {
            throw new Exception("Invalid table selected");
        }

        // Process each row
        while (($row = fgetcsv($file)) !== false) {
            if (empty($row[0])) continue;
            $value = trim($row[0]);

            $stmt = $conn->prepare("INSERT IGNORE INTO `$table` (`$column`) VALUES (?)");
            $stmt->bind_param('s', $value);
            if ($stmt->execute()) {
                $successCount++;
            } else {
                $errors[] = "Error inserting: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    fclose($file);

    // If there were any errors, throw them
    if (!empty($errors)) {
        throw new Exception("Completed with errors: " . implode(", ", $errors));
    }

    // Commit transaction
    $conn->commit();
    $_SESSION['upload_success'] = "Successfully inserted $successCount records.";

} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    $_SESSION['upload_error'] = $e->getMessage();
}

// Redirect back to form
header('Location: upload_csv_form.php');
exit();
?>
