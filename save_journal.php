<?php
include 'db/connection.php';
// Ensure this path is correct

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data and sanitize it
    $journal_title = $conn->real_escape_string($_POST['journal_title']);
    $author = $conn->real_escape_string($_POST['author']);
    $publisher = $conn->real_escape_string($_POST['publisher']);
    $year_of_publication = (int)$_POST['year_of_publication'];
    $issn_number = $conn->real_escape_string($_POST['issn_number']);

    // Prepare the SQL query
    $query = "INSERT INTO journals (journal_title, author, publisher, year_of_publication, issn_number) 
              VALUES ('$journal_title', '$author', '$publisher', '$year_of_publication', '$issn_number')";
    
    // Execute the query and check for success
    if ($conn->query($query)) {
        echo "Journal added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
    
    // Redirect to the journals page
    header("Location: publications_journals.php");
    exit;
}
?>
