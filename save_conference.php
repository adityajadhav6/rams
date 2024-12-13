<?php
include 'db/connection.php';
// Ensure this path is correct

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data and sanitize it
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $national_international = $conn->real_escape_string($_POST['national_international']);
    $year_of_publication = (int)$_POST['year'];
    $details = $conn->real_escape_string($_POST['details']);

    // Prepare the SQL query
    $query = "INSERT INTO conferences (title, author, national_international, year_of_publication, details) 
              VALUES ('$title', '$author', '$national_international', '$year_of_publication', '$details')";
    
    // Execute the query and check for success
    if ($conn->query($query)) {
        echo "Conference added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
    
    // Redirect to the conferences page
    header("Location: publications_conferences.php");
    exit;
}
?>