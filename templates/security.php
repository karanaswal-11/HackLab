<?php
session_start(); // Start session management

// Include database configuration
include_once('../config/config.php'); // Ensure the database connection is included

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seclev_submit'])) {
    // Retrieve and sanitize the selected security level
    $security_level = $_POST['security'];

    // Store the security level in a session variable
    $_SESSION['security_level'] = $security_level;

    // Log the action in the database
    if (isset($_SESSION['user_id'])) { // Ensure user ID is set in session
        $user_id = $_SESSION['user_id'];
        $action = 'Changed Security Level';
        $timestamp = date("Y-m-d H:i:s");

        // Prepare and execute the insert query
        $sql = "INSERT INTO session_logs (user_id, action, security_level, timestamp) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $action, $security_level, $timestamp);

        if (!$stmt->execute()) {
            // Error handling
            echo "Error logging the change: " . $stmt->error;
        }

        $stmt->close(); // Close the statement
    } else {
        echo "User ID not found in session.";
    }
}

// Fetch current security level from session
$current_security_level = isset($_SESSION['security_level']) ? $_SESSION['security_level'] : 'low'; // Default to 'low'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackLab Security</title>
    <link rel="stylesheet" href="../assets/css/security.css"> <!-- Adjusted link -->
    <style>
        /* Nav styling - fixed below the header */
        nav {
            background-image: url('../assets/image/header2.jpg'); /* Add your nav background image here */
            background-color: #333; /* Fallback color */
            background-size: cover;
            background-position: center;
            padding: 20px 0;
            position: fixed; /* Fix the nav bar */
            top: 20px; /* Align it just below the header */
            left: 0;
            right: 0;
            z-index: 999; /* Ensure it stays on top of the content */
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
            margin: 0;
            display: flex; /* Use flex for horizontal layout */
            justify-content: center; /* Center items */
            flex-wrap: wrap; /* Allow wrapping */
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 5px;
            transition: background-color 0.3s;
        }

        nav ul li a:hover {
            background-color: hwb(187 42% 18%);
            text-decoration: underline;
        }

        h2, h3 {
            color: #333;
            margin-bottom: 10px;
            cursor: pointer;
        }

        h2:hover, h3:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <h1 style="font-size: 40px"> 𝐒𝐞𝐜𝐮𝐫𝐢𝐭𝐲 𝐟𝐨𝐫 𝐇𝐚𝐜𝐤𝐋𝐚𝐛  </h1>
</header>

<nav>
    <ul>
        <li><a href="dashboard.html">Home</a></li>
        <li><a href="instructions.html">Instructions</a></li>
        <li><a href="setup.html">Setup</a></li>
        <li><a href="bruteforce.php">Brute Force</a></li>
        <li><a href="commandexecution.html">Command Execution</a></li>
        <li><a href="csrf.html">CSRF</a></li>
        <li><a href="fileinclusion.html">File Inclusion</a></li>
        <li><a href="sqlinjection.html">SQL Injection</a></li>
        <li><a href="sqlinjection(blind).html">SQL Injection (Blind)</a></li>
        <li><a href="upload.html">Upload</a></li>
        <li><a href="xssreflected.html">XSS Reflected</a></li>
        <li><a href="xssstored.html">XSS Stored</a></li>
        <li><a href="security.php">Hacklab Security</a></li>
        <li><a href="phpinfo.php">PHP Info</a></li>
    </ul>
</nav>

<div class="content">
    <h1>HackLab Security</h1>
    <br />

    <h2>Script Security</h2>
    <form action="security.php" method="POST">
        <p>Security Level is currently <em><?php echo htmlspecialchars($current_security_level); ?></em>.</p>
        <p>You can set the security level to low, medium, or high.</p>
        <p>The security level changes the vulnerability level of HackLab.</p>
        <select name="security">
            <option value="low" <?php echo ($current_security_level === 'low') ? 'selected' : ''; ?>>Low</option>
            <option value="medium" <?php echo ($current_security_level === 'medium') ? 'selected' : ''; ?>>Medium</option>
            <option value="high" <?php echo ($current_security_level === 'high') ? 'selected' : ''; ?>>High</option>
        </select>
        <input type="submit" value="Submit" name="seclev_submit">
    </form>

    <br />
    <hr />
    <br />

    <h2>PHPIDS</h2>
    <p><a href="http://hiderefer.com/?http://php-ids.org/" target="_blank">PHPIDS</a> v.0.6 (PHP-Intrusion Detection System) is a security layer for PHP-based web applications.</p>
    <p>You can enable PHPIDS across this site for the duration of your session.</p>
    <p>PHPIDS is currently <em>disabled</em>. [<a href="?phpids=on">enable PHPIDS</a>]</p>
    <p>[<a href="?test=%22><script>eval(window.name)</script>">Simulate attack</a>] - [<a href="ids_log.php">View IDS log</a>]</p>
    <div class="clear"></div>

    <div id="system_info">
        <div align="right">
            <b>Username:</b> <?php echo htmlspecialchars($_SESSION['username']); ?><br />
            <b>Security Level:</b> <?php echo htmlspecialchars($current_security_level); ?><br />
            <b>PHPIDS:</b> disabled
        </div>
    </div>
</div>

<footer style="font-size: 20px"> 🅷🅰🅲🅺🅻🅰🅱 </footer>

</body>
</html>
