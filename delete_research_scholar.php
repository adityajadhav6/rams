<?php
include 'db/connection.php';

// Check if the scholar ID is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $scholarId = intval($_GET['id']); // Ensure scholar ID is an integer

    // Delete the scholar from the database
    $delete_query = "DELETE FROM research_scholars WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    
    if ($stmt === false) {
        // Handle the error if the statement preparation fails
        die("<p style='color: red;'>Error preparing the query: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . "</p>");
    }

    $stmt->bind_param("i", $scholarId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirect back to the supervisor page or display a success message
        header("Location: supervisor.php");
        exit();
    } else {
        echo "<p style='color: red;'>Error deleting the scholar.</p>";
    }
} else {
    echo "<p style='color: red;'>No scholar ID provided or invalid ID.</p>";
}
?>
