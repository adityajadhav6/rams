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

// Handle form submission for adding new journal entries
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$isViewer && isset($_POST['add_journal'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $year_of_publication = $_POST['year_of_publication'];
    $issn_number = $_POST['issn_number'];

    $stmt = $conn->prepare("INSERT INTO journals (title, author, publisher, year_of_publication, issn) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $title, $author, $publisher, $year_of_publication, $issn_number);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Journal added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding journal: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle search functionality
$searchYear = $_GET['search_year'] ?? '';
$searchAuthor = $_GET['search_author'] ?? '';

$query = "SELECT * FROM journals WHERE 1=1";

if (!empty($searchYear)) {
    $query .= " AND year_of_publication = '" . $conn->real_escape_string($searchYear) . "'";
}
if (!empty($searchAuthor)) {
    $query .= " AND author LIKE '%" . $conn->real_escape_string($searchAuthor) . "%'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journals - Research Centre Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f5f7;
            padding: 20px;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        h1 {
            color: #343a40;
            text-align: center;
        }
        .form-group label {
            font-weight: bold;
            color: #495057;
        }
        .btn {
            margin-top: 10px;
        }
        .btn-primary:hover, .btn-secondary:hover {
            transform: translateY(-2px);
        }
        table thead {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Journals</h1>

        <!-- Display the form only if the user is not a viewer -->
        <?php if (!$isViewer): ?>
            <form method="post" action="">
                <input type="hidden" name="add_journal" value="1">
                <div class="form-group">
                    <label for="title">Journal Title:</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="author">Name of the Author:</label>
                    <input type="text" id="author" name="author" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="publisher">Publisher:</label>
                    <input type="text" id="publisher" name="publisher" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="year_of_publication">Year of Publication:</label>
                    <input type="number" id="year_of_publication" name="year_of_publication" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="issn_number">ISSN Number:</label>
                    <input type="text" id="issn_number" name="issn_number" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Save</button>
            </form>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="get" class="mt-4">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="search_year">Search by Year:</label>
                    <input type="number" id="search_year" name="search_year" class="form-control" value="<?= htmlspecialchars($searchYear) ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="search_author">Search by Author:</label>
                    <input type="text" id="search_author" name="search_author" class="form-control" value="<?= htmlspecialchars($searchAuthor) ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Search</button>
        </form>

        <!-- Journals Table -->
        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <th>Journal Title</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Year of Publication</th>
                    <th>ISSN Number</th>
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
                                <td>" . htmlspecialchars($row['title']) . "</td>
                                <td>" . htmlspecialchars($row['author']) . "</td>
                                <td>" . htmlspecialchars($row['publisher']) . "</td>
                                <td>" . htmlspecialchars($row['year_of_publication']) . "</td>
                                <td>" . htmlspecialchars($row['issn']) . "</td>";
                        if (!$isViewer) {
                            echo "<td>
                                    <a href='edit_journal.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete_journal.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                                  </td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . ($isViewer ? "5" : "6") . "' class='text-center'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="publications.php" class="btn btn-secondary">Back to Publications</a>
        </div>
    </div>
</body>
</html>
