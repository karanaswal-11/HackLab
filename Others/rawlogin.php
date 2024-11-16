<?php
session_start(); // Start session management
include '../config/config.php'; // Include the config file for database connection

// Check if the constants are already defined before defining them
if (!defined('DEFAULT_USERNAME')) {
    define('DEFAULT_USERNAME', 'admin'); // Your default username
}

if (!defined('DEFAULT_PASSWORD')) {
    define('DEFAULT_PASSWORD', 'password');  // Your default password
}

// Initialize error variable
$error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check against default credentials
    if ($username === DEFAULT_USERNAME && $password === DEFAULT_PASSWORD) {
        // Successful login
        $_SESSION['loggedin'] = true; // Store session variable
        $_SESSION['username'] = $username; // Store username in session
        
        // Redirect to dashboard after successful login
        header('Location: ../templates/dashboard.html');
        exit; // Ensure script stops after redirection
    } else {
        // Invalid credentials
        $error = 'Invalid username or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackLab - Login</title>
    <link rel="stylesheet" href="../Others/rawlogin.css"> <!-- Link to your login-specific styles -->
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <div class="textbox">
                    <input type="text" placeholder="Username" name="username" required>
                </div>
                <div class="textbox">
                    <input type="password" placeholder="Password" name="password" required>
                </div>
                <input type="submit" class="btn" value="Login">
                <?php if ($error): ?>
                    <div style="color: red; text-align: center;"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
