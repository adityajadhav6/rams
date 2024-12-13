<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$is_viewer = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'viewer';

// Fetch the grant type (faculty or student)
$grant_type = $_GET['type']; // 'faculty' or 'student'

// Define the table name based on grant type
$table_name = $grant_type === 'faculty' ? 'faculty_grants' : 'student_grants';

// Include database connection
require 'db_connect.php';

// Handle form submission for adding grants
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_viewer) {
    $project_name = $_POST['project_name'];
    $pi_name = $_POST['pi_name'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $amount = $_POST['amount'];
    $duration = $_POST['duration'];
    $funding_agency = $_POST['funding_agency'];

    $query = "INSERT INTO $table_name (project_name, pi_name, department, year, amount, duration, funding_agency)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssidsi', $project_name, $pi_name, $department, $year, $amount, $duration, $funding_agency);
    $stmt->execute();
}

// Fetch grants data
$query = "SELECT * FROM $table_name ORDER BY year DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo ucfirst($grant_type); ?> Grants</title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
<div class="container">
    <h1><?php echo ucfirst($grant_type); ?> Grants</h1>

    <!-- Display Add Grant Form -->
    <?php if (!$is_viewer): ?>
        <form method="POST">
            <input type="text" name="project_name" placeholder="Project Name" required>
            <input type="text" name="pi_name" placeholder="Principal Investigator" required>
            <input type="text" name="department" placeholder="Department" required>
            <input type="number" name="year" placeholder="Year of Award" required>
            <input type="number" step="0.01" name="amount" placeholder="Amount (in Lakhs)" required>
            <input type="text" name="duration" placeholder="Duration" required>
            <input type="text" name="funding_agency" placeholder="Funding Agency" required>
            <button type="submit">Add Grant</button>
        </form>
    <?php endif; ?>

    <!-- Display Grants Table -->
    <table>
        <thead>
        <tr>
            <th>Project Name</th>
            <th>Principal Investigator</th>
            <th>Department</th>
            <th>Year</th>
            <th>Amount (in Lakhs)</th>
            <th>Duration</th>
            <th>Funding Agency</th>
            <?php if (!$is_viewer): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['project_name']; ?></td>
                <td><?php echo $row['pi_name']; ?></td>
                <td><?php echo $row['department']; ?></td>
                <td><?php echo $row['year']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['duration']; ?></td>
                <td><?php echo $row['funding_agency']; ?></td>
                <?php if (!$is_viewer): ?>
                    <td>
                        <a href="edit_grant.php?id=<?php echo $row['id']; ?>&type=<?php echo $grant_type; ?>">Edit</a>
                        <a href="delete_grant.php?id=<?php echo $row['id']; ?>&type=<?php echo $grant_type; ?>">Delete</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Back to Home -->
    <a href="index.php">Back to Home</a>
</div>
</body>
</html>