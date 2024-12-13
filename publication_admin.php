<?php
include 'db/connection.php';

// Handle adding a new publication
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_publication'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publication_date = $_POST['publication_date'];

    $stmt = $conn->prepare("INSERT INTO publications (title, author, publication_date) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $title, $author, $publication_date);
    $stmt->execute();
}

// Handle deleting a publication
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM publications WHERE id = ?");
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();

    header('Location: publication_admin.php');
    exit;
}

// Fetch all publications
$result = $conn->query("SELECT * FROM publications");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publication Admin</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
            color: #007bff;
            animation: fadeIn 0.8s ease-in-out;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            animation: slideIn 0.8s ease-in-out;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.8s ease-in-out;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: linear-gradient(to right, #007bff, #0056b3);
            color: #fff;
            text-transform: uppercase;
        }

        tr:hover {
            background-color: #f1f3f5;
            transition: background-color 0.3s ease;
        }

        form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            animation: fadeIn 0.8s ease-in-out;
        }

        input, button {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            background: linear-gradient(to right, #007bff, #0056b3);
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background: linear-gradient(to right, #0056b3, #003e80);
            transform: translateY(-2px);
        }

        .btn-warning {
            background: linear-gradient(to right, #ffcc00, #e6b800);
            color: #fff;
        }

        .btn-warning:hover {
            background: linear-gradient(to right, #e6b800, #cc9900);
        }

        .btn-danger {
            background: linear-gradient(to right, #dc3545, #c82333);
            color: #fff;
        }

        .btn-danger:hover {
            background: linear-gradient(to right, #c82333, #a71d2a);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-10px);
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
    <div class="container">
        <h1>Publication Admin</h1>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publication Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['publication_date']); ?></td>
                    <td>
                        <a href="publication_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="publication_admin.php?delete_id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Add New Publication</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Title" required>
            <input type="text" name="author" placeholder="Author" required>
            <input type="date" name="publication_date" required>
            <button type="submit" name="add_publication">Add Publication</button>
        </form>
    </div>
</body>
</html>
