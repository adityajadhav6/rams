<?php
include 'db/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the SQL statement to delete the journal
    $stmt = $conn->prepare("DELETE FROM journals WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to the publications_journals.php without displaying any message
        header("Location: publications_journals.php");
        exit();
    } else {
        // Handle any error if needed
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to publications_journals.php if the ID is not set in the URL
    header("Location: publications_journals.php");
    exit();
}
?>
