<?php
include 'db/connection.php';

$id = $_GET['id']; // Get the ID from the URL
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the record
    $name = $_POST['name'];
    $post = $_POST['post'];

    $stmt = $conn->prepare("UPDATE advisory_committee SET name=?, post=? WHERE id=?");
    $stmt->bind_param('ssi', $name, $post, $id);
    $stmt->execute();

    header('Location: Supervisor.php'); // Redirect back to admin page
    exit;
}

// Fetch the existing record
$stmt = $conn->prepare("SELECT * FROM advisory_committee WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$advisory = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Advisory Committee Member</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 400px;
            animation: fadeIn 0.8s ease-in-out;
        }

        h1 {
            font-size: 24px;
            color: #343a40;
            margin-bottom: 20px;
            animation: slideIn 0.8s ease-in-out;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        .back-button {
            margin-top: 20px;
            display: inline-block;
            background: linear-gradient(to right, #6c757d, #5a6268);
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .back-button:hover {
            background: linear-gradient(to right, #5a6268, #444950);
            transform: translateY(-3px);
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
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Advisory Committee Member</h1>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($advisory['name']); ?>" required>
            
            <label for="post">Post:</label>
            <input type="text" id="post" name="post" value="<?php echo htmlspecialchars($advisory['post']); ?>" required>
            
            <button type="submit">Save Changes</button>
        </form>
        <a href="Supervisor.php" class="back-button">Back to Admin</a>
    </div>
</body>
</html>
