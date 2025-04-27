<?php
session_start();
require_once '../db_connect.php';

define('ADMIN_ACCESS', true);
include_once 'includes/nav.php';

// Check admin authentication
if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
    header('Location: login.php');
    exit();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get any messages from the upload process
$success = $_SESSION['upload_success'] ?? null;
$error = $_SESSION['upload_error'] ?? null;

// Clear the messages
unset($_SESSION['upload_success'], $_SESSION['upload_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV - Prompt Generator Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 800px; margin-top: 2rem; }
        .alert { margin: 1rem 0; }
        .form-group { margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload CSV Data</h1>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Upload Combined CSV</h3>
                    </div>
                    <div class="card-body">
                        <form action="upload_csv.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="upload_type" value="combined">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <div class="mb-3">
                                <label for="combinedCsv" class="form-label">Combined CSV File</label>
                                <input type="file" class="form-control" id="combinedCsv" name="csvFile" accept=".csv" required>
                                <div class="form-text">
                                    CSV should have these columns:<br>
                                    table_name,column_name,value<br>
                                    Example:<br>
                                    treatments,treatment_text,High quality masterpiece<br>
                                    art_styles,style_name,Watercolor<br>
                                    poses,pose_name,Standing
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload Combined CSV</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Upload Individual Table CSV</h3>
                    </div>
                    <div class="card-body">
                        <form action="upload_csv.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="upload_type" value="individual">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <div class="mb-3">
                                <label for="table" class="form-label">Select Table</label>
                                <select class="form-select" id="table" name="table" required>
                                    <option value="">Choose a table...</option>
                                    <option value="treatments">Treatments</option>
                                    <option value="art_styles">Art Styles</option>
                                    <option value="animal_characters">Animal Characters</option>
                                    <option value="poses">Poses</option>
                                    <option value="catchphrases">Catchphrases</option>
                                    <option value="humour_styles">Humour Styles</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="csvFile" class="form-label">CSV File</label>
                                <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
                                <div class="form-text">
                                    Single column CSV with header.<br>
                                    Example for treatments:<br>
                                    treatment_text<br>
                                    High quality masterpiece<br>
                                    Cinematic shot<br>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload CSV</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="admin_panel.php" class="btn btn-secondary">Back to Admin Panel</a>
        </div>

        <div class="mt-4">
            <h3>CSV Format Guidelines</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Table</th>
                            <th>Column Name</th>
                            <th>Example Values</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>treatments</td>
                            <td>treatment_text</td>
                            <td>High quality masterpiece, Cinematic shot</td>
                        </tr>
                        <tr>
                            <td>art_styles</td>
                            <td>style_name</td>
                            <td>Watercolor, Oil painting</td>
                        </tr>
                        <tr>
                            <td>animal_characters</td>
                            <td>character_name</td>
                            <td>Cat, Dog, Bird</td>
                        </tr>
                        <tr>
                            <td>poses</td>
                            <td>pose_name</td>
                            <td>Standing, Sitting, Running</td>
                        </tr>
                        <tr>
                            <td>catchphrases</td>
                            <td>catchphrase_text</td>
                            <td>Hello World, Good Morning</td>
                        </tr>
                        <tr>
                            <td>humour_styles</td>
                            <td>humour_name</td>
                            <td>Sarcastic, Witty, Silly</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
