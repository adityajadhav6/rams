<?php
// Include the connection file
include 'db/connection.php';

// Ensure an ID is provided for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing data for this book
    $query = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Book not found.";
        exit;
    }
    
    $book = $result->fetch_assoc();
} else {
    echo "No ID provided.";
    exit;
}

// If the form is submitted, update the book details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_name = $_POST['teacher_name'];
    $title = $_POST['title'];
    $national_international = $_POST['national_international'];
    $year = $_POST['year'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];

    // Update the book record
    $updateQuery = "UPDATE books SET teacher_name = ?, title = ?, national_international = ?, year = ?, isbn = ?, publisher = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sssiiss', $teacher_name, $title, $national_international, $year, $isbn, $publisher, $id);

    if ($stmt->execute()) {
        // Redirect after update
        header("Location: publications_books.php");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Research Centre Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Edit Book Entry</h1>

        <!-- Edit Form -->
        <form method="post">
            <div class="form-group">
                <label for="teacher_name">Name of the Teacher:</label>
                <input type="text" id="teacher_name" name="teacher_name" class="form-control" value="<?php echo htmlspecialchars($book['teacher_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="title">Title of the Book:</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="national_international">National / International:</label>
                <select id="national_international" name="national_international" class="form-control" required>
                    <option value="National" <?php echo ($book['national_international'] == 'National') ? 'selected' : ''; ?>>National</option>
                    <option value="International" <?php echo ($book['national_international'] == 'International') ? 'selected' : ''; ?>>International</option>
                </select>
            </div>

            <div class="form-group">
                <label for="year">Year of Publication:</label>
                <input type="number" id="year" name="year" class="form-control" value="<?php echo htmlspecialchars($book['year']); ?>" required>
            </div>

            <div class="form-group">
                <label for="isbn">ISBN/ISSN Number of the Proceeding:</label>
                <input type="text" id="isbn" name="isbn" class="form-control" value="<?php echo htmlspecialchars($book['isbn']); ?>" required>
            </div>

            <div class="form-group">
                <label for="publisher">Name of the Publisher:</label>
                <input type="text" id="publisher" name="publisher" class="form-control" value="<?php echo htmlspecialchars($book['publisher']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
