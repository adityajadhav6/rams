<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grants Received</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
            margin-top: 10%;
        }

        .button {
            display: inline-block;
            margin: 10px;
            padding: 15px 25px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .button.disabled {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Grants Received</h1>
        <a href="faculty_grants.php" class="button">Faculty Grants</a>
        <a href="student_grants.php" class="button">Student Grants</a>
        <div style="margin-top: 20px;">
            <a href="index.php" class="button" style="background-color: #6c757d;">Back to Home</a>
        </div>
    </div>
</body>
</html>
