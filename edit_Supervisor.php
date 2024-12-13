<?php
include 'db/connection.php';

$id = $_GET['id']; // Get the ID from the URL
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the record
    $name = $_POST['name'];
    $usn = $_POST['usn'];
    $guide = $_POST['guide'];

    $stmt = $conn->prepare("UPDATE supervisors SET name=?, usn=?, guide=? WHERE id=?");
    $stmt->bind_param('sssi', $name, $usn, $guide, $id);
    $stmt->execute();

    header('Location: Supervisor.php'); // Redirect back to admin page
    exit;
}

// Fetch the existing record
$stmt = $conn->prepare("SELECT * FROM supervisors WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$supervisor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supervisor</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .form-container {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            animation: fadeIn 1s ease-in-out;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #333;
            animation: slideInDown 1s ease-in-out;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: box-shadow 0.3s ease, border 0.3s ease;
        }

        input[type="text"]:focus {
            border: 1px solid #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
            outline: none;
        }

        button {
            background: linear-gradient(to right, #007bff, #0056b3);
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
            width: 100%;
        }

        button:hover {
            background: linear-gradient(to right, #0056b3, #003e80);
            transform: translateY(-2px);
        }

        .back-button {
            margin-top: 20px;
            display: inline-block;
            background: #6c757d;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .back-button:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInDown {
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
    <div class="form-container">
        <h1>Edit Supervisor</h1>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($supervisor['name']); ?>" required>
            
            <label for="usn">USN:</label>
            <input type="text" id="usn" name="usn" value="<?php echo htmlspecialchars($supervisor['usn']); ?>" required>
            
            <label for="guide">Guide:</label>
            <input type="text" id="guide" name="guide" value="<?php echo htmlspecialchars($supervisor['guide']); ?>" required>
            
            <button type="submit">Save Changes</button>
        </form>
        <a href="Supervisor.php" class="back-button">Back to Admin</a>
    </div>
</body>
</html>
