<?php
include 'db/connection.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $conn->query("DELETE FROM advisory_committee WHERE id = $id");
    header("Refresh: 3; url=Supervisor.php"); // Redirect with a delay
    $message = "Record deleted successfully. Redirecting to Admin...";
} else {
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #f6f8fa, #e9ecef);
            animation: fadeIn 0.8s ease-in-out;
        }

        .message-box {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: slideDown 0.6s ease-in-out;
        }

        .message-box h1 {
            font-size: 24px;
            margin: 0 0 10px;
            color: #343a40;
        }

        .message-box p {
            font-size: 16px;
            color: #6c757d;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h1>Success!</h1>
        <p><?php echo isset($message) ? htmlspecialchars($message) : 'Redirecting to Admin...'; ?></p>
    </div>
</body>
</html>
