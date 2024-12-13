<?php
include 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_name = $_POST['teacher_name'];
    $title = $_POST['title'];
    $national_international = $_POST['national_international'];
    $year = $_POST['year'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO books (teacher_name, title, national_international, year, isbn, publisher) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $teacher_name, $title, $national_international, $year, $isbn, $publisher);

    if ($stmt->execute()) {
        // Redirect back to the publications_books.php without displaying any message
        header("Location: publications_books.php");
        exit();
    } else {
        // Handle any error if needed
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>