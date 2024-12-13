<?php
session_start();
include 'db/connection.php'; // Ensure this includes your database connection

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Get the journal ID from the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid journal ID.</div>";
    exit();
}
$journal_id = intval($_GET['id']);

// Fetch the journal details from the database
$query = $conn->prepare("SELECT * FROM journals WHERE id = ?");
$query->bind_param("i", $journal_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-danger'>Journal not found.</div>";
    exit();
}
$journal = $result->fetch_assoc();

// Handle form submission for updating the journal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $journal_title = $_POST['journal_title'] ?? '';
    $author = $_POST['author'] ?? '';
    $publisher = $_POST['publisher'] ?? '';
    $year_of_publication = $_POST['year_of_publication'] ?? '';
    $issn_number = $_POST['issn_number'] ?? '';

    // Update the journal in the database
    $update_query = $conn->prepare("UPDATE journals SET title = ?, author = ?, publisher = ?, year_of_publication = ?, issn = ? WHERE id = ?");
    $update_query->bind_param("sssssi", $journal_title, $author, $publisher, $year_of_publication, $issn_number, $journal_id);

    if ($update_query->execute()) {
        echo "<div class='alert alert-success'>Journal updated successfully!</div>";
        // Optionally redirect to a different page
        // header("Location: journals.php");
    } else {
        echo "<div class='alert alert-danger'>Error updating journal: " . $update_query->error . "</div>";
    }
    $update_query->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Journal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Journal Entry</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="journal_title">Journal Title:</label>
                <input type="text" id="journal_title" name="journal_title" class="form-control" value="<?php echo htmlspecialchars($journal['title'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" class="form-control" value="<?php echo htmlspecialchars($journal['author'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="publisher">Publisher:</label>
                <input type="text" id="publisher" name="publisher" class="form-control" value="<?php echo htmlspecialchars($journal['publisher'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="year_of_publication">Year of Publication:</label>
                <input type="number" id="year_of_publication" name="year_of_publication" class="form-control" value="<?php echo htmlspecialchars($journal['year_of_publication'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="issn_number">ISSN Number:</label>
                <input type="text" id="issn_number" name="issn_number" class="form-control" value="<?php echo htmlspecialchars($journal['issn'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="publications_journals.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
