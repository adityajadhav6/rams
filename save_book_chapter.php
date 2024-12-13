
<?php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $chapter_title = trim($_POST['chapter_title']);
    $book_title = trim($_POST['book_title']);
    $author = trim($_POST['author']);

    // Input validation
    if (empty($chapter_title) || empty($book_title) || empty($author)) {
        die("All fields are required.");
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO book_chapters (chapter_title, book_title, author) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $chapter_title, $book_title, $author);

    if ($stmt->execute()) {
        // Redirect after successful insertion
        header("Location: publications_book_chapters.php");
        exit();
    } else {
        // Display error in a user-friendly way
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8') . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
