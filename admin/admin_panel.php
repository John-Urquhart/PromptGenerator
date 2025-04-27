<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prompt Generator Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .admin-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .admin-nav { margin: 2rem 0; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .stat-box { padding: 1rem; border: 1px solid #dee2e6; border-radius: 0.25rem; text-align: center; }
        .stat-box .count { font-size: 2rem; font-weight: bold; color: #0d6efd; }
        .admin-actions { margin-top: 2rem; }
        .action-card { margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Prompt Generator Admin Panel</h1>
        
        <div class="admin-nav">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="upload_csv_form.php">Upload CSV Data</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../setup_database.php">Database Setup</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="admin-dashboard">
            <h2>Quick Stats</h2>
            <?php
            // Get counts from each table
            $tables = [
                'poses' => 'Poses',
                'animal_characters' => 'Animal Characters',
                'art_styles' => 'Art Styles',
                'humour_styles' => 'Humour Styles',
                'catchphrases' => 'Catchphrases',
                'treatments' => 'Treatments'
            ];

            echo '<div class="stats-grid">';
            foreach ($tables as $table => $label) {
                $result = $conn->query("SELECT COUNT(*) as count FROM `$table`");
                $count = $result ? $result->fetch_object()->count : '0';
                echo "<div class='stat-box'>";
                echo "<h3>" . htmlspecialchars($label) . "</h3>";
                echo "<p class='count'>" . htmlspecialchars($count) . "</p>";
                echo "</div>";
            }
            echo '</div>';
            ?>
        </div>

        <div class="admin-actions">
            <div class="row">
                <div class="col-md-4">
                    <div class="card action-card">
                        <div class="card-body">
                            <h5 class="card-title">Database Setup</h5>
                            <p class="card-text">Create or update database tables and indexes.</p>
                            <a href="../setup_database.php" class="btn btn-primary">Run Setup</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card action-card">
                        <div class="card-body">
                            <h5 class="card-title">Upload Data</h5>
                            <p class="card-text">Upload CSV files to populate dropdown lists.</p>
                            <a href="upload_csv_form.php" class="btn btn-primary">Upload CSV</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card action-card">
                        <div class="card-body">
                            <h5 class="card-title">View Frontend</h5>
                            <p class="card-text">Go to the main prompt generator page.</p>
                            <a href="../index.html" class="btn btn-primary">View Site</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>