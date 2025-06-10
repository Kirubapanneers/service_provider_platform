<?php
// Start session to store user data
session_start();

require_once 'db_config.php';

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the submitted form data
$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];
$confirm_pass = $_POST['confirm_password'];

// Check if password and confirm_password match
if ($pass !== $confirm_pass) {
    echo "<script>alert('Passwords do not match!'); window.location.href = 'servicers_reg.html';</script>";
    exit();
}

// Hash the password fields before storing
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Use prepared statement to safely insert into DB
$sql = "INSERT INTO servicers (username, email, password, confirm_password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $user, $email, $hashed_password, $hashed_password);

if ($stmt->execute()) {
    // Set session variable for the user
    $_SESSION['username'] = $user;

    echo "<script>
        alert('Registration successful! Welcome, " . $user . "');
        window.location.href = 'supplier_form.php';
    </script>";
} else {
    echo "<script>alert('Registration failed: " . $conn->error . "');</script>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
