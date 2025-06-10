<?php
session_start();
session_destroy(); // Destroy the session
header("Location: home.php"); // Redirect to the homepage
exit;
?>