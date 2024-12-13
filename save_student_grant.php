<?php
include 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize input data
    $project_name = trim($_POST['project_name'] ?? '');
    $student_name = trim($_POST['student_name'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $year_of_award = trim($_POST['year_of_award'] ?? '');
    $amount_sanctioned = trim($_POST['amount'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $funding_agency = trim($_POST['funding_agency'] ?? '');

    // Check if all fields are provided
    if (
        !empty($project_name) && 
        !empty($student_name) && 
        !empty($department) && 
        !empty($year_of_award) && 
        !empty($amount_sanctioned) && 
        !empty($duration) && 
        !empty($funding_agency)
    ) {
        try {
            // Prepare and bind statement
            $stmt = $conn->prepare(
                "INSERT INTO student_grants 
                (project_name, student_name, department, year_of_award, amount_sanctioned, duration, funding_agency) 
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            if ($stmt === false) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }

            $stmt->bind_param(
                "sssssss", 
                $project_name, 
                $student_name, 
                $department, 
                $year_of_award, 
                $amount_sanctioned, 
                $duration, 
                $funding_agency
            );

            // Execute statement and handle success
            if ($stmt->execute()) {
                header("Location: student_grants.php?success=1");
                exit();
            } else {
                throw new Exception("Failed to execute statement: " . $stmt->error);
            }

            $stmt->close();
        } catch (Exception $e) {
            // Print detailed error message for debugging
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Redirect back with an error message if fields are missing
        header("Location: student_grants.php?error=1");
        exit();
    }
}

$conn->close();
?>