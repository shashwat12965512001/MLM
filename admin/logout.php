<?php
require "../config/config.php";

// Check if session is already started
if (session_status() == PHP_SESSION_NONE) {
	// Session is not started
	// Start the session
	session_start();
}

// Adding log out activity
addActivity($_SESSION['mlm_user_id'], "Logged Out!");

// After logout
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to index.php
header("Location: ../");
exit; // Make sure to exit after redirecting to prevent further execution of the script

