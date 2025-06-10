<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #f4f4f4;
        }
        .auth-buttons {
            display: flex;
            gap: 10px;
        }
        .auth-buttons a, .user-info a {
            text-decoration: none;
            color: white;
            background-color: blue;
            padding: 10px;
            border-radius: 5px;
        }
        .user-info {
            font-size: 18px;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">Service Provider</div>

    <div class="navigation">
        <a href="home.php">Home</a>
        <a href="#">About Us</a>
    </div>

    <div class="user-area">
        <?php if (isset($_SESSION['username'])): ?>
            <!-- Show 'Your Account' if logged in -->
            <div class="user-info">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> |
                <a href="account.php">Your Account</a> |
                <a href="logout.php" style="color: red;">Logout</a>
            </div>
        <?php else: ?>
            <!-- Show login/signup buttons if not logged in -->
            <div class="auth-buttons">
                <a href="login.html">Login</a>
                <a href="signup.html">Signup</a>
            </div>
        <?php endif; ?>
    </div>
</header>

<!-- Main Content -->
<div class="content">
    <h1>Welcome to our service platform</h1>
    <!-- Add more content as needed -->
</div>

</body>
</html>