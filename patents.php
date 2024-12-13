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
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }

        /* Header with Background Image */
        .header {
            background: url('PHOTO-2024-12-14-00-29-02.jpg') no-repeat center center/cover;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .header h1 {
            font-size: 2.5em;
            margin: 0;
            font-weight: bold;
        }

        .home-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #007bff;
            color: #fff;
            padding: 15px 30px;
            border-radius: 40px;
            text-decoration: none;
            font-size: 1.3em;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .home-button:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 40px;
        }

        .nav-tabs .nav-link {
            font-weight: bold;
            color: #007bff;
        }

        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }

        .form-control {
            border-radius: 5px;
        }

        .table {
            margin-top: 20px;
        }

        .table th, .table td {
            text-align: center;
        }

        .table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #ddd;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .search-bar {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Header Section -->
<div class="header">
    <h1>Patents - Research Centre Management</h1>
</div>

<!-- Back Button -->
<a href="index.php" class="home-button">Home</a>

<div class="container mt-4">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#published">Patents Published</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#granted">Patents Granted</a>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- Patents Published -->
        <div id="published" class="tab-pane fade show active">
            <h2>Published Patents</h2>
            <table class="table table-bordered table-striped">
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
                    if ($result && $result->num_rows > 0) {
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
                                        <a href='edit_patent.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='delete_patent.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                    </td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No published patents found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Patents Granted -->
        <div id="granted" class="tab-pane fade">
            <h2>Granted Patents</h2>
            <table class="table table-bordered table-striped">
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
                    if ($result && $result->num_rows > 0) {
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
                                        <a href='edit_patent.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='delete_patent.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                    </td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No granted patents found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
