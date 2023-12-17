<?
// logout.php - destroys session and returns to login form
session_start();

// destroy all session variables
session_destroy();

// redirect browser back to login page
header("Location: login.php");
?>