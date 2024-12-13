<?php
session_start();
include 'db/connection.php'; // Ensure this includes your database connection

// Check if the user is logged in and has the right permissions
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login if not logged in
    header('Location: login.php');
    exit();
}

// Check if the user is a viewer
$isViewer = (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'viewer');

// Handle form submission for adding new book chapters
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$isViewer) {
    $teacher_name = $_POST['teacher_name'];
    $title = $_POST['title'];
    $national_international = $_POST['national_international'];
    $year = $_POST['year'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];
    $author = isset($_POST['author']) ? $_POST['author'] : NULL; // Allow NULL for author

    // Prepare an SQL statement for inserting the data
    $stmt = $conn->prepare("INSERT INTO book_chapters (teacher_name, title, author, national_international, year, isbn, publisher) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiss", $teacher_name, $title, $author, $national_international, $year, $isbn, $publisher);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Book chapter added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding book chapter: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Chapters - Research Centre Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #1e90ff;
            animation: fadeIn 1s ease-in-out;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            animation: slideIn 1s ease-in-out;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
        }
        table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }
        button, .btn {
            background-color: #1e90ff;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        button:hover, .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
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
                transform: translateY(10px);
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
        <h1>Book Chapters</h1>

        <!-- Display the form only if the user is not a viewer -->
        <?php if (!$isViewer): ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="teacher_name">Name of the Teacher:</label>
                    <input type="text" id="teacher_name" name="teacher_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="title">Title of the Chapter:</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="author">Author of the Chapter (optional):</label>
                    <input type="text" id="author" name="author" class="form-control">
                </div>
                <div class="form-group">
                    <label for="national_international">National / International:</label>
                    <select id="national_international" name="national_international" class="form-control">
                        <option value="National">National</option>
                        <option value="International">International</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">Year of Publication:</label>
                    <input type="number" id="year" name="year" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN/ISSN Number of the Book:</label>
                    <input type="text" id="isbn" name="isbn" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="publisher">Name of the Publisher:</label>
                    <input type="text" id="publisher" name="publisher" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-block">Save</button>
            </form>
        <?php else: ?>
            <p class="text-center text-muted">You have read-only access. You cannot add or modify records.</p>
        <?php endif; ?>

        <h2 class="mt-4">Existing Book Chapters</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Teacher Name</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>National/International</th>
                    <th>Year</th>
                    <th>ISBN/ISSN</th>
                    <th>Publisher</th>
                    <?php if (!$isViewer): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM book_chapters");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['teacher_name']) . "</td>
                                <td>" . htmlspecialchars($row['title']) . "</td>
                                <td>" . htmlspecialchars($row['author']) . "</td>
                                <td>" . htmlspecialchars($row['national_international']) . "</td>
                                <td>" . htmlspecialchars($row['year']) . "</td>
                                <td>" . htmlspecialchars($row['isbn']) . "</td>
                                <td>" . htmlspecialchars($row['publisher']) . "</td>";
                        if (!$isViewer) {
                            echo "<td>
                                    <a href='edit_book_chapter.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete_book_chapter.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                                  </td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . ($isViewer ? "7" : "8") . "' class='text-center'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <div class="text-center mt-4">
            <a href="publications.php" class="btn btn-secondary">Back to Publications</a>
        </div>
    </div>
</body>
</html>
