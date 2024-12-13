<?php
include 'db/connection.php';

// Check if the scholar ID is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $scholarId = intval($_GET['id']); // Ensure scholar ID is an integer

    // Fetch the scholar details
    $query = "SELECT * FROM research_scholars WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        // Handle the error if the statement preparation fails
        die("<p style='color: red;'>Error preparing the query: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . "</p>");
    }

    $stmt->bind_param("i", $scholarId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $scholar = $result->fetch_assoc();
    } else {
        echo "<p style='color: red;'>Scholar not found.</p>";
        exit();
    }
} else {
    echo "<p style='color: red;'>No scholar ID provided or invalid ID.</p>";
    exit();
}

// Handle form submission to update scholar details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scholar_name = $_POST['research_scholar_name'];
    $phd_status = $_POST['phd_status'];

    // Update the scholar record in the database
    $update_query = "UPDATE research_scholars SET scholar_name = ?, phd_status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    
    if ($stmt === false) {
        die("<p style='color: red;'>Error preparing the query: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . "</p>");
    }

    $stmt->bind_param("ssi", $scholar_name, $phd_status, $scholarId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirect to the supervisor page after successful update
        header("Location: fetch_research_scholars.php?supervisor_id=" . $scholar['supervisor_id']);
        exit();
    } else {
        echo "<p style='color: red;'>Error updating the scholar.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Research Scholar</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Edit Research Scholar</h1>
    <form action="edit_research_scholar.php?id=<?php echo $scholarId; ?>" method="POST">
        <label for="research_scholar_name">Research Scholar Name:</label>
        <input type="text" name="research_scholar_name" value="<?php echo htmlspecialchars($scholar['scholar_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
        
        <label for="phd_status">Ph.D Status:</label>
        <select name="phd_status" required>
            <option value="Completed" <?php echo ($scholar['phd_status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
            <option value="Thesis submitted" <?php echo ($scholar['phd_status'] === 'Thesis submitted') ? 'selected' : ''; ?>>Thesis submitted</option>
            <option value="Course Work Completed" <?php echo ($scholar['phd_status'] === 'Course Work Completed') ? 'selected' : ''; ?>>Course Work Completed</option>
            <option value="Applied for Course Work" <?php echo ($scholar['phd_status'] === 'Applied for Course Work') ? 'selected' : ''; ?>>Applied for Course Work</option>
            <option value="Registered" <?php echo ($scholar['phd_status'] === 'Registered') ? 'selected' : ''; ?>>Registered</option>
        </select>

        <button type="submit">Update Scholar</button>
    </form>

    <a href="fetch_research_scholars.php?supervisor_id=<?php echo $scholar['supervisor_id']; ?>">Back to Research Scholars</a>
</body>
</html>
