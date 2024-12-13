<?php
include 'db/connection.php';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM supervisors WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<div class='message-container'>
                <h1>Deleting Supervisor</h1>
                <p>The supervisor has been successfully deleted.</p>
                <a href='Supervisor.php'>Back to Admin Panel</a>
              </div>";
    } else {
        echo "<div class='message-container'>
                <h1>Error</h1>
                <p>Error deleting supervisor: " . $stmt->error . "</p>
                <a href='admin.php'>Back to Admin Panel</a>
              </div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleting Supervisor</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .message-container {
            background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeIn 1s ease-in-out, scaleUp 0.5s ease-in-out;
        }

        h1 {
            font-size: 28px;
            color: #495057;
            margin-bottom: 20px;
            animation: slideIn 1s ease-in-out;
        }

        p {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            background: linear-gradient(to right, #007bff, #0056b3);
            color: #ffffff;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 10px;
            font-size: 16px;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        a:hover {
            background: linear-gradient(to right, #0056b3, #003e80);
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes scaleUp {
            from {
                transform: scale(0.9);
            }
            to {
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
</body>
</html>