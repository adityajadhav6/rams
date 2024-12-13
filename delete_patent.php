<?php
session_start();
include 'db/connection.php';

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Check if the user is a viewer
$is_viewer = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'viewer';
if ($is_viewer) {
    header('Location: patents.php'); // Redirect viewers to the patents page
    exit();
}

// Get the patent ID from the query string
if (!isset($_GET['id'])) {
    header('Location: patents.php'); // Redirect if no ID is provided
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM patents WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: patents.php');
} else {
    echo "Error: " . $stmt->error;
}
?>
