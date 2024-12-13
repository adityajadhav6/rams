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
if ($is_viewer) {
    header('Location: patents.php'); // Redirect viewers to the patents page
    exit();
}

// Get the patent ID from the query string
if (!isset($_GET['id'])) {
    header('Location: patents.php'); // Redirect if no ID is provided
    exit();
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM patents WHERE id = $id");
if ($result->num_rows === 0) {
    header('Location: patents.php'); // Redirect if no matching record is found
    exit();
}

$patent = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_name = $_POST['teacher_name'];
    $department = $_POST['department'];
    $title = $_POST['title'];
    $application_no = $_POST['application_no'];
    $year = $_POST['year'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE patents SET teacher_name = ?, department = ?, title = ?, application_no = ?, year = ?, status = ? WHERE id = ?");
    $stmt->bind_param('ssssisi', $teacher_name, $department, $title, $application_no, $year, $status, $id);

    if ($stmt->execute()) {
        header('Location: patents.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patent</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>Edit Patent</h1>
    <form method="post">
        <div class="form-group">
            <label for="teacher_name">Name of the Teacher:</label>
            <input type="text" id="teacher_name" name="teacher_name" class="form-control" value="<?= htmlspecialchars($patent['teacher_name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" class="form-control" value="<?= htmlspecialchars($patent['department']) ?>" required>
        </div>
        <div class="form-group">
            <label for="title">Title of the Invention:</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($patent['title']) ?>" required>
        </div>
        <div class="form-group">
            <label for="application_no">Application Number:</label>
            <input type="text" id="application_no" name="application_no" class="form-control" value="<?= htmlspecialchars($patent['application_no']) ?>" required>
        </div>
        <div class="form-group">
            <label for="year">Year:</label>
            <input type="number" id="year" name="year" class="form-control" value="<?= htmlspecialchars($patent['year']) ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" id="status" name="status" class="form-control" value="<?= htmlspecialchars($patent['status']) ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="patents.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
