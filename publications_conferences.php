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

// Handle form submission for adding new conference entries
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_conference']) && !$isViewer) {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $national_international = $_POST['national_international'] ?? '';
    $year_of_publication = $_POST['year_of_publication'] ?? '';
    $details = $_POST['details'] ?? '';

    if (!empty($title) && !empty($author) && !empty($national_international) && !empty($year_of_publication) && !empty($details)) {
        $stmt = $conn->prepare("INSERT INTO conferences (title, author, national_international, year_of_publication, details) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $title, $author, $national_international, $year_of_publication, $details);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Conference added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error adding conference: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>All fields are required!</div>";
    }
}

// Handle search functionality
$searchQuery = "SELECT * FROM conferences";
$searchParams = [];
if (isset($_GET['search'])) {
    $authorSearch = $_GET['author'] ?? '';
    $yearSearch = $_GET['year_of_publication'] ?? '';
    $conditions = [];
    if (!empty($authorSearch)) {
        $conditions[] = "author LIKE ?";
        $searchParams[] = "%" . $authorSearch . "%";
    }
    if (!empty($yearSearch)) {
        $conditions[] = "year_of_publication = ?";
        $searchParams[] = $yearSearch;
    }
    if (!empty($conditions)) {
        $searchQuery .= " WHERE " . implode(" AND ", $conditions);
    }
}
$stmt = $conn->prepare($searchQuery);
if (!empty($searchParams)) {
    $types = str_repeat("s", count($searchParams));
    $stmt->bind_param($types, ...$searchParams);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conferences - Research Centre Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Conferences</h1>
        <?php if (!$isViewer): ?>
            <form method="post" action="">
                <input type="hidden" name="add_conference" value="1">
                <div class="form-group">
                    <label for="title">Title of the Paper:</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="author">Name of the Author:</label>
                    <input type="text" id="author" name="author" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="national_international">National / International:</label>
                    <select id="national_international" name="national_international" class="form-control">
                        <option value="National">National</option>
                        <option value="International">International</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year_of_publication">Year of Publication:</label>
                    <input type="number" id="year_of_publication" name="year_of_publication" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="details">Details of the Article:</label>
                    <input type="text" id="details" name="details" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Save</button>
            </form>
        <?php endif; ?>

        <form method="get" action="" class="mt-4">
            <h2>Search Conferences</h2>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="author">Author:</label>
                    <input type="text" id="author" name="author" class="form-control" value="<?= htmlspecialchars($_GET['author'] ?? '') ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="year_of_publication">Year of Publication:</label>
                    <input type="number" id="year_of_publication" name="year_of_publication" class="form-control" value="<?= htmlspecialchars($_GET['year_of_publication'] ?? '') ?>">
                </div>
            </div>
            <button type="submit" name="search" class="btn btn-primary">Search</button>
        </form>

        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>National/International</th>
                    <th>Year</th>
                    <th>Details</th>
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
                                <td>" . htmlspecialchars($row['national_international']) . "</td>
                                <td>" . htmlspecialchars($row['year_of_publication']) . "</td>
                                <td>" . htmlspecialchars($row['details']) . "</td>";
                        if (!$isViewer) {
                            echo "<td>
                                    <a href='edit_conference.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete_conference.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
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

        <div class="text-center mt-4">
            <a href="publications.php" class="btn btn-secondary">Back to Publications</a>
        </div>
    </div>
</body>
</html>
