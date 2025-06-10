<?php
session_start();

// Secure DB connection
require_once 'db_config.php'; // Ensure this file is added to .gitignore

// Check connection
if ($conn->connect_error) {
    die("Connection error. Please try again later.");
}

// Get and sanitize input
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($username) || empty($password)) {
    echo "<script>alert('Username and password are required.');
    window.location.href = 'login.html';
    </script>";
    exit();
}

// Prepare secure SQL statement
$stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check user existence and verify password
if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        // Success: set session and redirect
        $_SESSION['username'] = $user['username'];
        echo "<script>
            alert('Login successful! Welcome.');
            window.location.href = 'home.html';
        </script>";
    } else {
        // Invalid password
        echo "<script>
            alert('Incorrect password!');
            window.location.href = 'login.html';
        </script>";
    }
} else {
    // User not found
    echo "<script>
        alert('No account found with that username!');
        window.location.href = 'login.html';
    </script>";
}

// Clean up
$stmt->close();
$conn->close();
?>
