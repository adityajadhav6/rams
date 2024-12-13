<?php
include 'db/connection.php';

// Handle form submission to save research scholar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate the form input
    if (isset($_POST['research_scholar_name']) && isset($_POST['phd_status']) && isset($_POST['supervisor_id'])) {
        $research_scholar_name = $_POST['research_scholar_name'];
        $phd_status = $_POST['phd_status'];
        $supervisor_id = $_POST['supervisor_id'];

        // Prepare and execute the insert query
        $query = "INSERT INTO research_scholars (scholar_name, phd_status, supervisor_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("ssi", $research_scholar_name, $phd_status, $supervisor_id);
            
            if ($stmt->execute()) {
                echo "<p>Research scholar added successfully.</p>";
                header("Location: fetch_research_scholars.php?supervisor_id=$supervisor_id");
                exit();
            } else {
                echo "<p>Error adding research scholar: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p>Failed to prepare the query.</p>";
        }
    } else {
        echo "<p>All fields are required!</p>";
    }
}
?>
