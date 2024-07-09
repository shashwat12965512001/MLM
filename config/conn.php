<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if session is already started
if (session_status() == PHP_SESSION_NONE) {
	// Session is not started
	// Start the session
	session_start();
}

// Initialize the connection variables
$host = "host";
$username = "username";
$password = "password";
$database = "database";

$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
	die('Connection failed: ' . $conn->connect_error);
}
