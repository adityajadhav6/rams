<?php
include 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']); // Sanitize input
    $usn = trim($_POST['usn']);  // Sanitize input
    $guide = trim($_POST['guide']); // Sanitize input

    if (!empty($name) && !empty($usn) && !empty($guide)) {
        try {
            // Use INSERT...ON DUPLICATE KEY UPDATE to handle duplicate key scenarios
            $query = "INSERT INTO supervisors (name, usn, guide) 
                      VALUES (?, ?, ?)
                      ON DUPLICATE KEY UPDATE 
                          name = VALUES(name), 
                          guide = VALUES(guide)";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param('sss', $name, $usn, $guide);
                $stmt->execute();
                $stmt->close();
                header("Location: Supervisor.php"); // Redirect to the admin panel
                exit();
            } else {
                throw new Exception("Failed to prepare the statement.");
            }
        } catch (Exception $e) {
            // Log the error for debugging purposes
            error_log("Error saving supervisor: " . $e->getMessage());
            echo "<p style='color:red;'>An error occurred while saving the supervisor. Please try again.</p>";
        }
    } else {
        echo "<p style='color:red;'>All fields are required. Please fill in all the details.</p>";
    }
} else {
    header("Location: admin.php"); // Redirect to the admin panel if accessed directly
    exit();
}
?>
a