<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grants Received</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: url('PHOTO-2024-12-14-00-33-58.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9); /* Add transparency */
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 40px 50px;
            width: 100%;
            max-width: 500px;
        }

        h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 30px;
        }

        .button {
            display: inline-block;
            margin: 15px;
            padding: 15px 30px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.5em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
        }

        .button:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        .button:active {
            background-color: #004085;
            transform: translateY(0);
        }

        .button.disabled {
            pointer-events: none;
            opacity: 0.6;
            cursor: not-allowed;
        }

        .button-back {
            background-color: #6c757d;
        }

        .button-back:hover {
            background-color: #5a6268;
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 40px;
            }

            h1 {
                font-size: 2em;
            }

            .button {
                font-size: 1.2em;
                padding: 12px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Grants Received</h1>
        <a href="faculty_grants.php" class="button">Faculty Grants</a>
        <a href="student_grants.php" class="button">Student Grants</a>
        <div style="margin-top: 20px;">
            <a href="index.php" class="button button-back">Back to Home</a>
        </div>
    </div>
</body>
</html>
