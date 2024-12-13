<?php
session_start();
include 'db/connection.php';

// Redirect if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Save patent data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_name = $_POST['teacher_name'];
    $department = $_POST['department'];
    $title = $_POST['title'];
    $application_no = $_POST['application_no'];
    $year = $_POST['year'];
    $status = $_POST['status'];
    $type = $_POST['type'];

    $stmt = $conn->prepare("INSERT INTO patents (teacher_name, department, title, application_no, year, status, type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssiss', $teacher_name, $department, $title, $application_no, $year, $status, $type);

    if ($stmt->execute()) {
        header('Location: patents.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
