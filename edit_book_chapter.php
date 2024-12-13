<?php 
include 'db/connection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    die('Error: Missing ID parameter.');
}

$query = "SELECT * FROM book_chapters WHERE id = $id";
$result = $conn->query($query);

if (!$result) {
    die('Query failed: ' . $conn->error);
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die('Error: No record found.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_name = $conn->real_escape_string($_POST['teacher_name']);
    $title = $conn->real_escape_string($_POST['title']);
    $national_international = $conn->real_escape_string($_POST['national_international']);
    $year = intval($_POST['year']);
    $isbn = $conn->real_escape_string($_POST['isbn']);
    $publisher = $conn->real_escape_string($_POST['publisher']);

    $update_query = "UPDATE book_chapters SET 
        teacher_name='$teacher_name', 
        title='$title', 
        national_international='$national_international', 
        year=$year, 
        isbn='$isbn', 
        publisher='$publisher' 
        WHERE id=$id";

    if ($conn->query($update_query)) {
        echo "Record updated successfully.";
        header("Location: publications_book_chapters.php");
        exit();
    } else {
        die("Error updating record: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book Chapter</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Edit Book Chapter</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="teacher_name">Name of the Teacher:</label>
                <input type="text" id="teacher_name" name="teacher_name" class="form-control" value="<?php echo htmlspecialchars($row['teacher_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="title">Title of the Chapter:</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="national_international">National / International:</label>
                <select id="national_international" name="national_international" class="form-control">
                    <option value="National" <?php echo ($row['national_international'] == 'National') ? 'selected' : ''; ?>>National</option>
                    <option value="International" <?php echo ($row['national_international'] == 'International') ? 'selected' : ''; ?>>International</option>
                </select>
            </div>
            <div class="form-group">
                <label for="year">Year of Publication:</label>
                <input type="number" id="year" name="year" class="form-control" value="<?php echo $row['year']; ?>" required>
            </div>
            <div class="form-group">
                <label for="isbn">ISBN/ISSN Number of the Book:</label>
                <input type="text" id="isbn" name="isbn" class="form-control" value="<?php echo htmlspecialchars($row['isbn']); ?>" required>
            </div>
            <div class="form-group">
                <label for="publisher">Name of the Publisher:</label>
                <input type="text" id="publisher" name="publisher" class="form-control" value="<?php echo htmlspecialchars($row['publisher']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
