<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page (or homepage)
header("Location: ../index.php"); // Change to index.php if needed
exit;
?>
