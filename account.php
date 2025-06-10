<?php
session_start();

require_once 'db_config.php'; // Load DB credentials from .env securely

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current logged-in user's details
$currentUser = $_SESSION['username'];
// $sql = "SELECT * FROM users WHERE username='$currentUser'";
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $currentUser);
$stmt->execute();
$result = $stmt->get_result();

// $result = $conn->query($sql);

// If the user is found in the database, display their details
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .account-details {
            max-width: 400px;
            margin: 0 auto;
        }
        .account-details h2 {
            text-align: center;
        }
        .account-details p {
            font-size: 18px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="account-details">
    <h2>Your Account Details</h2>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <!-- Add more user details as needed -->
</div>

</body>
</html>