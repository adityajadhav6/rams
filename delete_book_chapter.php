<?php
include 'db/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM book_chapters WHERE id = $id";

    if ($conn->query($query)) {
        echo "Book Chapter deleted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
header("Location: publications_book_chapters.php");
exit;
?>
