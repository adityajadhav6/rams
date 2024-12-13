<?php
session_start();
include 'db/connection.php'; // Ensure this includes your database connection

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Check if the user is a viewer
$is_viewer = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'viewer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patents - Research Centre Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .home-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.2em;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .home-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <!-- Home Button -->
    <a href="index.php" class="home-button">Home</a>

    <div class="container mt-4">
        <h1 class="text-center">Patents</h1>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#published">Patents Published</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#granted">Patents Granted</a>
            </li>
        </ul>
        <div class="tab-content mt-3">
            <!-- Patents Published -->
            <div id="published" class="tab-pane fade show active">
                <?php if (!$is_viewer): ?>
                    <form method="post" action="save_patent.php">
                        <input type="hidden" name="type" value="published">
                        <div class="form-group">
                            <label for="teacher_name">Name of the Teacher:</label>
                            <input type="text" id="teacher_name" name="teacher_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="department">Department:</label>
                            <input type="text" id="department" name="department" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="title">Title of the Invention:</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="application_no">Application Number:</label>
                            <input type="text" id="application_no" name="application_no" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="year">Year:</label>
                            <input type="number" id="year" name="year" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <input type="text" id="status" name="status" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                <?php endif; ?>
                <h2 class="mt-4">Published Patents</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Title</th>
                            <th>Application No</th>
                            <th>Year</th>
                            <th>Status</th>
                            <?php if (!$is_viewer): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM patents WHERE type = 'published'");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['teacher_name']) . "</td>
                                        <td>" . htmlspecialchars($row['department']) . "</td>
                                        <td>" . htmlspecialchars($row['title']) . "</td>
                                        <td>" . htmlspecialchars($row['application_no']) . "</td>
                                        <td>" . htmlspecialchars($row['year']) . "</td>
                                        <td>" . htmlspecialchars($row['status']) . "</td>";
                                if (!$is_viewer) {
                                    echo "<td>
                                            <a href='edit_patent.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='delete_patent.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                        </td>";
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Patents Granted -->
            <div id="granted" class="tab-pane fade">
                <?php if (!$is_viewer): ?>
                    <form method="post" action="save_patent.php">
                        <input type="hidden" name="type" value="granted">
                        <div class="form-group">
                            <label for="teacher_name_granted">Name of the Teacher:</label>
                            <input type="text" id="teacher_name_granted" name="teacher_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="department_granted">Department:</label>
                            <input type="text" id="department_granted" name="department" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="title_granted">Title of the Invention:</label>
                            <input type="text" id="title_granted" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="application_no_granted">Application Number:</label>
                            <input type="text" id="application_no_granted" name="application_no" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="year_granted">Year:</label>
                            <input type="number" id="year_granted" name="year" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="status_granted">Status:</label>
                            <input type="text" id="status_granted" name="status" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                <?php endif; ?>
                <h2 class="mt-4">Granted Patents</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Title</th>
                            <th>Application No</th>
                            <th>Year</th>
                            <th>Status</th>
                            <?php if (!$is_viewer): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM patents WHERE type = 'granted'");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['teacher_name']) . "</td>
                                        <td>" . htmlspecialchars($row['department']) . "</td>
                                        <td>" . htmlspecialchars($row['title']) . "</td>
                                        <td>" . htmlspecialchars($row['application_no']) . "</td>
                                        <td>" . htmlspecialchars($row['year']) . "</td>
                                        <td>" . htmlspecialchars($row['status']) . "</td>";
                                if (!$is_viewer) {
                                    echo "<td>
                                            <a href='edit_patent.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='delete_patent.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                        </td>";
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
