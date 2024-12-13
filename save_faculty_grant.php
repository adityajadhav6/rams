<?php
include 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize input data
    $grant_title = trim($_POST['grant_title'] ?? ''); // Grant title
    $principal_investigator = trim($_POST['principal_investigator'] ?? ''); // Principal Investigator
    $department = trim($_POST['department'] ?? ''); // Department
    $year_of_award = trim($_POST['year_of_award'] ?? ''); // Year of Award
    $amount_sanctioned = trim($_POST['amount_sanctioned'] ?? ''); // Amount sanctioned
    $duration = trim($_POST['duration'] ?? ''); // Duration of the grant
    $funding_agency = trim($_POST['funding_agency'] ?? ''); // Funding agency

    // Check if all fields are provided
    if (
        !empty($grant_title) && 
        !empty($principal_investigator) && 
        !empty($department) && 
        !empty($year_of_award) && 
        !empty($amount_sanctioned) && 
        !empty($duration) && 
        !empty($funding_agency)
    ) {
        try {
            // Prepare and bind statement
            $stmt = $conn->prepare(
                "INSERT INTO grants 
                (grant_title, principal_investigator, department, year_of_award, amount_sanctioned, duration, funding_agency, type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'faculty')"
            );

            if ($stmt === false) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }

            $stmt->bind_param(
                "sssssss", 
                $grant_title, 
                $principal_investigator, 
                $department, 
                $year_of_award, 
                $amount_sanctioned, 
                $duration, 
                $funding_agency
            );

            // Execute statement and handle success
            if ($stmt->execute()) {
                header("Location: faculty_grants.php?success=1");
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
        header("Location: faculty_grants.php?error=1");
        exit();
    }
}

$conn->close();
?>