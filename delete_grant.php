<?php
session_start();
include 'db/connection.php';

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Check if the user is a viewer
$is_viewer = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'viewer';

// Check if an ID is passed via URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. No ID provided.");
}

$id = intval($_GET['id']); // Sanitize and convert to integer

// Fetch existing grant data for confirmation
$query = "SELECT * FROM grants WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Grant not found for the given ID: " . $id);
}

$grant = $result->fetch_assoc();

// Check if deletion form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare and bind the delete statement
    $delete_stmt = $conn->prepare("DELETE FROM grants WHERE id = ?");
    $delete_stmt->bind_param("i", $id);

    if ($delete_stmt->execute()) {
        // Redirect to the grants page after successful deletion
        header("Location: faculty_grants.php?success=3");
        exit();
    } else {
        $error_message = "Error: " . $delete_stmt->error;
    }

    $delete_stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Grant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f1f1;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .confirmation-message {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .form-group {
            display: flex;
            justify-content: center;
        }
        .form-group button {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #c82333;
        }
        .form-group a {
            text-decoration: none;
            color: #007bff;
            display: block;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Delete Grant</h1>
    <div class="confirmation-message">
        <p>Are you sure you want to delete the following grant?</p>
        <p><strong>Project Name:</strong> <?php echo htmlspecialchars($grant['project_name']); ?></p>
        <p><strong>PI/Co-Investigator:</strong> <?php echo htmlspecialchars($grant['principal_investigator']); ?></p>
        <p><strong>Department:</strong> <?php echo htmlspecialchars($grant['department']); ?></p>
        <p><strong>Year of Award:</strong> <?php echo htmlspecialchars($grant['year_of_award']); ?></p>
        <p><strong>Amount Sanctioned:</strong> <?php echo htmlspecialchars($grant['amount_sanctioned']); ?> Lakhs</p>
        <p><strong>Duration:</strong> <?php echo htmlspecialchars($grant['duration']); ?></p>
        <p><strong>Funding Agency:</strong> <?php echo htmlspecialchars($grant['funding_agency']); ?></p>
    </div>

    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <button type="submit">Delete Grant</button>
        </div>
        <div class="form-group">
            <a href="faculty_grants.php">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>