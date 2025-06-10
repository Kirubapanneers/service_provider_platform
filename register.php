<?php
session_start();
require_once 'db_config.php';

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed. Please try again later.");
}

// Retrieve form data
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Basic validation
if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
    echo "<script>alert('Please fill in all fields.'); window.location.href = 'register.html';</script>";
    exit();
}

if ($password !== $confirmPassword) {
    echo "<script>alert('Passwords do not match!'); window.location.href = 'register.html';</script>";
    exit();
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert both password and confirm_password into DB
$stmt = $conn->prepare("INSERT INTO users (username, email, password, confirm_password) VALUES (?, ?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registration successful! Welcome, " . htmlspecialchars($username) . "');
            window.location.href = 'home.html';
        </script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.'); window.location.href = 'register.html';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Server error. Please try again later.'); window.location.href = 'register.html';</script>";
}

$conn->close();
?>
