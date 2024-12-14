<?php
session_start();
include 'db/connection.php'; // Ensure this includes your database connection

// Check if the user is logged in and has the right permissions
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
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

    $stmt = $conn->prepare("INSERT INTO book_chapters (teacher_name, title, author, national_international, year, isbn, publisher) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiss", $teacher_name, $title, $author, $national_international, $year, $isbn, $publisher);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Book chapter added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding book chapter: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle search filters
$searchYear = isset($_GET['search_year']) ? $_GET['search_year'] : '';
$searchAuthor = isset($_GET['search_author']) ? $_GET['search_author'] : '';
$searchQuery = "SELECT * FROM book_chapters WHERE 1";

if ($searchYear) {
    $searchQuery .= " AND year = '$searchYear'";
}
if ($searchAuthor) {
    $searchQuery .= " AND teacher_name LIKE '%$searchAuthor%'";
}

$result = $conn->query($searchQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Chapters - Research Centre Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f4f8; padding: 20px; }
        h1 { text-align: center; color: #1e90ff; animation: fadeIn 1s ease-in-out; }
        .container { background-color: #ffffff; padding: 20px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); animation: slideIn 1s ease-in-out; }
        table { margin-top: 20px; width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        button, .btn { background-color: #1e90ff; color: white; border: none; padding: 10px; border-radius: 5px; transition: background-color 0.3s ease; }
        button:hover, .btn:hover { background-color: #0056b3; }
        .back-button { margin-top: 30px; display: flex; justify-content: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Chapters</h1>
        
        <!-- Search Form -->
        <form method="get" action="" class="form-inline mb-3">
            <div class="form-group mr-2">
                <label for="search_year" class="mr-2">Search by Year:</label>
                <input type="number" id="search_year" name="search_year" class="form-control" value="<?= htmlspecialchars($searchYear) ?>">
            </div>
            <div class="form-group mr-2">
                <label for="search_author" class="mr-2">Search by Author:</label>
                <input type="text" id="search_author" name="search_author" class="form-control" value="<?= htmlspecialchars($searchAuthor) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="book_chapters.php" class="btn btn-secondary ml-2">Clear</a>
        </form>

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
        <?php endif; ?>

        <!-- Table -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Chapter Author</th>
                    <th>Title</th>
                    <th>Book Author</th>
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

        <!-- Back Button -->
        <div class="back-button">
            <a href="index.php" class="btn btn-lg btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
