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

// Handle form submission for adding new conference entries
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$isViewer) {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $national_international = $_POST['national_international'] ?? '';
    $year_of_publication = $_POST['year_of_publication'] ?? '';
    $details = $_POST['details'] ?? '';

    // Validate input fields
    if (empty($title) || empty($author) || empty($national_international) || empty($year_of_publication) || empty($details)) {
        echo "<div class='alert alert-danger'>All fields are required!</div>";
    } elseif (!is_numeric($year_of_publication) || strlen($year_of_publication) !== 4) {
        echo "<div class='alert alert-danger'>Year of Publication must be a valid 4-digit year!</div>";
    } else {
        // Prepare an SQL statement for inserting the data
        $stmt = $conn->prepare("INSERT INTO conferences (title, author, national_international, year_of_publication, details) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $title, $author, $national_international, $year_of_publication, $details);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Conference added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error adding conference: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conferences - Research Centre Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #343a40;
            animation: fadeIn 1s ease-in-out;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
            animation: slideIn 0.8s ease-in-out;
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
        form {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }
        input, select, button {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Conferences</h1>

        <!-- Display the form only if the user is not a viewer -->
        <?php if (!$isViewer): ?>
            <form method="post" action="">
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
        <?php else: ?>
        <?php endif; ?>

        <!-- Display existing conferences -->
        <table class="table table-bordered table-hover">
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
                $result = $conn->query("SELECT * FROM conferences");
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
                                    <a href='delete_conference.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
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
