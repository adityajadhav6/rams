<?php
session_start();
include 'db/connection.php'; // Ensure this includes your database connection

// Check if the user is logged in and has the right permissions
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$isViewer = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'viewer';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle form submission for adding a book
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$isViewer && isset($_POST['add_book'])) {
    $teacher_name = $_POST['teacher_name'];
    $title = $_POST['title'];
    $national_international = $_POST['national_international'];
    $year = $_POST['year'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];

    $stmt = $conn->prepare("INSERT INTO books (teacher_name, title, national_international, year, isbn, publisher) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $teacher_name, $title, $national_international, $year, $isbn, $publisher);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Book added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding book: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle search functionality
$searchYear = isset($_GET['year']) ? $_GET['year'] : '';
$searchTeacher = isset($_GET['teacher_name']) ? $_GET['teacher_name'] : '';
$query = "SELECT * FROM books WHERE 1=1";

if ($searchYear !== '') {
    $query .= " AND year = " . intval($searchYear);
}

if ($searchTeacher !== '') {
    $query .= " AND teacher_name LIKE '%" . $conn->real_escape_string($searchTeacher) . "%'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books - Research Centre Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Books</h1>

        <!-- Search Form -->
        <form method="get" action="" class="form-inline mb-4">
            <div class="form-group mr-2">
                <input type="text" name="teacher_name" class="form-control" placeholder="Search by Teacher Name" value="<?php echo htmlspecialchars($searchTeacher); ?>">
            </div>
            <div class="form-group mr-2">
                <input type="number" name="year" class="form-control" placeholder="Search by Year" value="<?php echo htmlspecialchars($searchYear); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Add Book Form -->
        <?php if (!$isViewer): ?>
            <form method="post" action="">
                <input type="hidden" name="add_book" value="1">
                <div class="form-group">
                    <label for="teacher_name">Name of the Teacher:</label>
                    <input type="text" id="teacher_name" name="teacher_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="title">Title of the Book:</label>
                    <input type="text" id="title" name="title" class="form-control" required>
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
                    <label for="isbn">ISBN/ISSN Number:</label>
                    <input type="text" id="isbn" name="isbn" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="publisher">Name of the Publisher:</label>
                    <input type="text" id="publisher" name="publisher" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Save</button>
            </form>
        <?php endif; ?>

        <!-- Books Table -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Title</th>
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
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['teacher_name']) . "</td>
                                <td>" . htmlspecialchars($row['title']) . "</td>
                                <td>" . htmlspecialchars($row['national_international']) . "</td>
                                <td>" . htmlspecialchars($row['year']) . "</td>
                                <td>" . htmlspecialchars($row['isbn']) . "</td>
                                <td>" . htmlspecialchars($row['publisher']) . "</td>";
                        if (!$isViewer) {
                            echo "<td>
                                    <a href='edit_book.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete_book.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                                  </td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . ($isViewer ? "6" : "7") . "' class='text-center'>No records found</td></tr>";
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
