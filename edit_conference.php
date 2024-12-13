<?php
// Include the connection file
include 'db/connection.php';

// Ensure an ID is provided for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing data for this conference
    $query = "SELECT * FROM conferences WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Conference not found.";
        exit;
    }
    
    $conference = $result->fetch_assoc();
} else {
    echo "No ID provided.";
    exit;
}

// If the form is submitted, update the conference details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $national_international = $_POST['national_international'];
    $year = $_POST['year'];
    $details = $_POST['details'];

    // Update the conference record
    $updateQuery = "UPDATE conferences SET title = ?, author = ?, national_international = ?, year_of_publication = ?, details = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sssssi', $title, $author, $national_international, $year, $details, $id);

    if ($stmt->execute()) {
        // Redirect after update
        header("Location: publications_conferences.php");
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
    <title>Edit Conference - Research Centre Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Edit Conference Entry</h1>

        <!-- Edit Form -->
        <form method="post">
            <div class="form-group">
                <label for="title">Title of the Paper:</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($conference['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="author">Name of the Author:</label>
                <input type="text" id="author" name="author" class="form-control" value="<?php echo htmlspecialchars($conference['author']); ?>" required>
            </div>

            <div class="form-group">
                <label for="national_international">National / International:</label>
                <select id="national_international" name="national_international" class="form-control" required>
                    <option value="National" <?php echo ($conference['national_international'] == 'National') ? 'selected' : ''; ?>>National</option>
                    <option value="International" <?php echo ($conference['national_international'] == 'International') ? 'selected' : ''; ?>>International</option>
                </select>
            </div>

            <div class="form-group">
                <label for="year">Year of Publication:</label>
                <input type="number" id="year" name="year" class="form-control" value="<?php echo htmlspecialchars($conference['year_of_publication']); ?>" required>
            </div>

            <div class="form-group">
                <label for="details">Details of the Article:</label>
                <input type="text" id="details" name="details" class="form-control" value="<?php echo htmlspecialchars($conference['details']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
