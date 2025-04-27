<?php
session_start();
require_once '../db_connect.php';

// If already logged in, redirect to admin panel
if (isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true) {
    header('Location: admin_panel.php');
    exit();
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // TEMPORARY: Hardcoded credentials - change these!
    if ($username === 'admin' && $password === 'adminpass123') {
        $_SESSION['admin_authenticated'] = true;
        header('Location: admin_panel.php');
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Prompt Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 400px;
            margin: 4rem auto;
            padding: 2rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Admin Login</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        
        <p class="mt-3 text-center">
            <a href="../index.html">Return to Main Site</a>
        </p>
    </div>
</body>
</html><?php
session_start();
session_destroy();
header('Location: login.php');
exit();
?><li class="nav-item">
    <a class="nav-link" href="logout.php">Logout</a>
</li><?php
session_start();
session_destroy();
header('Location: login.php');
exit();
?>