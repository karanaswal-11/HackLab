<?php
session_start(); // Start session management
include '../config/config.php'; // Include the config file for database connection

// Initialize error variable
$error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check against default credentials first
    if ($username === DEFAULT_USERNAME && $password === DEFAULT_PASSWORD) {
        // Successful login with default credentials
        $_SESSION['loggedin'] = true; // Store session variable
        $_SESSION['username'] = $username; // Store username in session

        // Redirect to dashboard after successful login
        header('Location: ../templates/dashboard.html');
        exit; 
        // Ensure script stops after redirection
    }

    // Fetch user details from the database for user login
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1"); // Adjust table name if necessary
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Compare input password with the password stored in the database (plaintext comparison)
        if ($password === $user['password']) {
            // Successful login with database credentials
            $_SESSION['loggedin'] = true; // Store session variable
            $_SESSION['username'] = $username; // Store username in session
            
            // Prepare to insert session data
            $session_id = session_id(); // Get the current session ID
            $ip_address = $_SERVER['REMOTE_ADDR']; // Get the user's IP address
            $user_agent = $_SERVER['HTTP_USER_AGENT']; // Get the user's browser user agent
            
            // Prepare the statement for inserting session data
            $insert_stmt = $conn->prepare("INSERT INTO sessions (session_id, user_id, ip_address, user_agent) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("siss", $session_id, $user['id'], $ip_address, $user_agent);
            $insert_stmt->execute(); // Execute the insert statement
            
            // Redirect to dashboard after successful login
            header('Location: ../templates/dashboard.html');
            exit; // Ensure script stops after redirection
        } else {
            // Invalid password
            $error = 'Invalid username or password!';
        }
    } else {
        // User not found in the database
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
    <link rel="stylesheet" href="../assets/css/logi.css"> 
</head>
<body>
    <!-- Video Background -->
    <video autoplay muted loop id="bg-video">
        <source src="../assets/video/background.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

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
