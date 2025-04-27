<?php
session_start();
require_once '../db_connect.php';

// Check admin authentication
if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
    header('Location: login.php');
    exit();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include_once 'admin_panel.php';
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
        <h2>Upload CSV File to Populate Dropdown Lists</h2>

        <?php
        if (isset($_SESSION['upload_message'])) {
            $alertClass = isset($_SESSION['upload_success']) && $_SESSION['upload_success'] 
                ? 'alert-success' 
                : 'alert-danger';
            echo '<div class="alert ' . $alertClass . '">';
            echo htmlspecialchars($_SESSION['upload_message']);
            echo '</div>';
            unset($_SESSION['upload_message']);
            unset($_SESSION['upload_success']);
        }
        ?>

        <div class="card">
            <div class="card-body">
                <form action="upload_csv.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    
                    <div class="form-group">
                        <label for="table">Select Table to Populate:</label>
                        <select name="table" id="table" required class="form-control">
                            <option value="">-- Select Table --</option>
                            <option value="poses">Poses</option>
                            <option value="animal_characters">Animal Characters</option>
                            <option value="art_styles">Art Styles</option>
                            <option value="humour_styles">Humour Styles</option>
                            <option value="catchphrases">Catchphrases</option>
                            <option value="treatments">Treatments</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="csvFile">Choose CSV File:</label>
                        <input type="file" name="csvFile" id="csvFile" accept=".csv" required class="form-control">
                        <small class="form-text text-muted">
                            File must be in CSV format with a header row. Only the first column will be used.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary">Upload and Insert</button>
                    <a href="admin_panel.php" class="btn btn-secondary">Back to Admin Panel</a>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4>CSV Format Guidelines</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">File must be in CSV format (.csv extension)</li>
                    <li class="list-group-item">First row should be the header row (will be skipped)</li>
                    <li class="list-group-item">Only the first column will be used for data</li>
                    <li class="list-group-item">Empty rows will be skipped</li>
                    <li class="list-group-item">Each value should be unique within its table</li>
                </ul>
            </div>
        </div>

        <div class="mt-4">
            <h4>Example CSV Format:</h4>
            <pre class="bg-light p-3 rounded">
pose_name
Tree Pose
Warrior Pose
Mountain Pose</pre>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
