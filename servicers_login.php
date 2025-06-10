<?php
session_start();
require_once 'db_config.php';

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed. Please try again later.");
}

// Retrieve form input safely
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Basic validation
if (empty($username) || empty($password)) {
    echo "<script>alert('Please enter both username and password.'); window.location.href = 'servicers_login.html';</script>";
    exit();
}

// Prepare SQL query to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM servicers WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result && $result->num_rows > 0) {
    $userData = $result->fetch_assoc();

    // Verify hashed password
    if (password_verify($password, $userData['password'])) {
        $_SESSION['username'] = $username;

        echo "<script>
            alert('Login successful! Welcome, " . htmlspecialchars($username) . "');
            window.location.href = 'supplier_form.php';
        </script>";
    } else {
        echo "<script>
            alert('Incorrect password!');
            window.location.href = 'servicers_login.html';
        </script>";
    }
} else {
    echo "<script>
        alert('No account found with that username!');
        window.location.href = 'servicers_login.html';
    </script>";
}

$stmt->close();
$conn->close();
?>
