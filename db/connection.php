<?php
// Enable error reporting for development (remove or disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "root"; // Use 'root' if that's the default password for MAMP MySQL
$dbname = "research_centre";
$port = 8889; // Add your MySQL port

// Create the connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8 for proper encoding
if (!$conn->set_charset("utf8")) {
    die("Error setting UTF-8 charset: " . $conn->error);
}

// Optional debugging message (remove in production)
// echo "Database connected successfully!";
?>
