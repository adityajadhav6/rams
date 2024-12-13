<?php
session_start(); // Start the session at the beginning of the script
include 'db/connection.php';

// Check if the ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. No ID provided.");
}

$id = intval($_GET['id']); // Sanitize and convert to integer

// Fetch existing grant data from the database
$query = "SELECT * FROM grants WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Grant not found.");
}

$grant = $result->fetch_assoc();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for CSRF token to prevent CSRF attacks
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    // Collect and sanitize input data
    $project_name = htmlspecialchars(trim($_POST['project_name'] ?? ''), ENT_QUOTES);
    $principal_investigator = htmlspecialchars(trim($_POST['principal_investigator'] ?? ''), ENT_QUOTES);
    $department = htmlspecialchars(trim($_POST['department'] ?? ''), ENT_QUOTES);
    $year_of_award = htmlspecialchars(trim($_POST['year_of_award'] ?? ''), ENT_QUOTES);
    $amount_sanctioned = htmlspecialchars(trim($_POST['amount_sanctioned'] ?? ''), ENT_QUOTES);
    $duration = htmlspecialchars(trim($_POST['duration'] ?? ''), ENT_QUOTES);
    $funding_agency = htmlspecialchars(trim($_POST['funding_agency'] ?? ''), ENT_QUOTES);

    // Validate that all fields are provided
    if (
        !empty($project_name) &&
        !empty($principal_investigator) &&
        !empty($department) &&
        !empty($year_of_award) &&
        !empty($amount_sanctioned) &&
        !empty($duration) &&
        !empty($funding_agency)
    ) {
        // Prepare and bind the update statement
        $update_stmt = $conn->prepare(
            "UPDATE grants SET grant_title = ?, principal_investigator = ?, department = ?, year_of_award = ?, amount_sanctioned = ?, duration = ?, funding_agency = ? WHERE id = ?"
        );
        $update_stmt->bind_param(
            "sssssssi",
            $project_name,
            $principal_investigator,
            $department,
            $year_of_award,
            $amount_sanctioned,
            $duration,
            $funding_agency,
            $id
        );

        if ($update_stmt->execute()) {
            header("Location: faculty_grants.php?success=1");
            exit();
        } else {
            $error_message = "Error updating grant: " . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        $error_message = "All fields are required.";
    }
}

// Generate CSRF token for form submission
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty Grant</title>
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
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-group button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Grant</h1>
    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <!-- CSRF token field -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="form-group">
            <label for="project_name">Project Name</label>
            <input type="text" name="project_name" id="project_name" value="<?php echo htmlspecialchars($grant['project_name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="principal_investigator">Principal Investigator/Co-Investigator</label>
            <input type="text" name="principal_investigator" id="principal_investigator" value="<?php echo htmlspecialchars($grant['principal_investigator'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" name="department" id="department" value="<?php echo htmlspecialchars($grant['department'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="year_of_award">Year of Award</label>
            <input type="number" name="year_of_award" id="year_of_award" value="<?php echo htmlspecialchars($grant['year_of_award'] ?? ''); ?>" min="1900" max="<?php echo date('Y'); ?>" required>
        </div>
        <div class="form-group">
            <label for="amount_sanctioned">Amount (Lakhs)</label>
            <input type="number" name="amount_sanctioned" id="amount_sanctioned" value="<?php echo htmlspecialchars($grant['amount_sanctioned'] ?? ''); ?>" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="duration">Duration</label>
            <input type="text" name="duration" id="duration" value="<?php echo htmlspecialchars($grant['duration'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="funding_agency">Funding Agency</label>
            <input type="text" name="funding_agency" id="funding_agency" value="<?php echo htmlspecialchars($grant['funding_agency'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <button type="submit">Update Grant</button>
        </div>
    </form>
</div>
</body>
</html>
