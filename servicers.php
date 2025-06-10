<?php
session_start();
require_once 'db_config.php';

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed. Please try again later.");
}

// Get and sanitize login input
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($username) || empty($password)) {
    echo "<script>alert('Username and password are required.'); window.location.href = 'login.html';</script>";
    exit();
}

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT password FROM servicers WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify hashed password
    if (password_verify($password, $row['password'])) {
        $_SESSION['username'] = $username;
        echo "<script>
            alert('Login successful! Welcome, " . htmlspecialchars($username) . "');
            window.location.href = 'supplier_form.php';
        </script>";
    } else {
        echo "<script>alert('Incorrect password.'); window.location.href = 'login.html';</script>";
    }
} else {
    echo "<script>alert('No account found with that username.'); window.location.href = 'login.html';</script>";
}

$stmt->close();
$conn->close();
?>
