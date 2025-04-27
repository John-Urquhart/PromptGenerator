<?php
session_start();
require_once 'db_connect.php';

// Check admin authentication
if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
    header('Location: login.php');
    exit();
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['upload_message'] = "Invalid security token.";
    $_SESSION['upload_success'] = false;
    header('Location: upload_csv_form.php');
    exit();
}

// Allow only specific tables and their columns
$allowedTables = [
    'poses' => 'pose_name',
    'animal_characters' => 'character_name',
    'art_styles' => 'style_name',
    'humour_styles' => 'humour_name',
    'catchphrases' => 'catchphrase_text',
    'treatments' => 'treatment_text'
];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table'] ?? '';
    $file = $_FILES['csvFile'] ?? null;

    if (!$table || !$file) {
        $_SESSION['upload_message'] = "Missing table selection or file upload.";
        $_SESSION['upload_success'] = false;
        header('Location: upload_csv_form.php');
        exit();
    }

    // Validate allowed table
    if (!isset($allowedTables[$table])) {
        $_SESSION['upload_message'] = "Invalid table selection.";
        $_SESSION['upload_success'] = false;
        header('Location: upload_csv_form.php');
        exit();
    }

    // Validate uploaded file
    if ($file['type'] !== 'text/csv' && pathinfo($file['name'], PATHINFO_EXTENSION) !== 'csv') {
        $_SESSION['upload_message'] = "Only CSV files are allowed.";
        $_SESSION['upload_success'] = false;
        header('Location: upload_csv_form.php');
        exit();
    }

    // Open the uploaded CSV file
    if (($handle = fopen($file['tmp_name'], 'r')) !== false) {
        $column = $allowedTables[$table];
        $successCount = 0;
        $errorCount = 0;
        $row = 0;

        // Start transaction
        $conn->begin_transaction();

        try {
            // Prepare statement once outside the loop
            $stmt = $conn->prepare("INSERT INTO `$table` (`$column`) VALUES (?)");

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $row++;
                
                // Skip header row
                if ($row === 1) {
                    continue;
                }

                $value = trim($data[0] ?? '');

                if ($value !== '') {
                    $stmt->bind_param("s", $value);

                    if ($stmt->execute()) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        error_log("Error inserting row $row: " . $stmt->error);
                    }
                }
            }

            // If no errors, commit the transaction
            if ($errorCount === 0) {
                $conn->commit();
                $_SESSION['upload_message'] = "Successfully inserted $successCount records.";
                $_SESSION['upload_success'] = true;
            } else {
                throw new Exception("Some records failed to insert.");
            }

        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['upload_message'] = "Error: " . $e->getMessage() . " ($successCount successful, $errorCount failed)";
            $_SESSION['upload_success'] = false;
        }

        fclose($handle);
        $stmt->close();
    } else {
        $_SESSION['upload_message'] = "Error reading CSV file.";
        $_SESSION['upload_success'] = false;
    }

    header('Location: upload_csv_form.php');
    exit();
}

// If we get here, it wasn't a POST request
header('Location: upload_csv_form.php');
exit();
