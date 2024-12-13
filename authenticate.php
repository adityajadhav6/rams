<?php
session_start(); // Start the session
include 'db/connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Replace with your user authentication logic
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['username'] = $username; // Store username in session
        header('Location: index.php'); // Redirect to index.php
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid username or password'); window.location.href='login.php';</script>";
    }
} else {
    header('Location: login.php'); // Redirect back to login if accessed directly
}
?>
