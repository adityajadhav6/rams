<?php 
include 'db/connection.php';

// Fetch Supervisors and Advisory Committee data
$supervisors_result = $conn->query("SELECT * FROM supervisors");
$advisory_committee_result = $conn->query("SELECT * FROM advisory_committee");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Centre Management - Supervisors & Advisory Committee</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        /* Hover effect on supervisor names */
        .hover-link {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
        }
        .hover-link:hover {
            text-decoration: underline;
        }
        .button {
            display: block;
            margin: 20px auto;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .form-container {
            margin-bottom: 20px;
        }
        .form-container input, .form-container select {
            padding: 10px;
            width: 100%;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-container button {
            background-color: #28a745;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Research Centre Management</h1>
    <div class="container">

        <!-- Add Supervisor Form -->
        <div class="form-container">
            <h2>Add Supervisor</h2>
            <form action="save_Supervisor.php" method="POST">
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="usn" placeholder="USN" required>
                <input type="text" name="guide" placeholder="Guide" required>
                <button type="submit">Add Supervisor</button>
            </form>
        </div>

        <!-- Add Advisory Committee Form -->
        <div class="form-container">
            <h2>Add Advisory Committee Member</h2>
            <form action="save_advisory_committee.php" method="POST">
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="post" placeholder="Post" required>
                <button type="submit">Add Advisory Member</button>
            </form>
        </div>

        <!-- Supervisors Section -->
        <div class="viewer-section">
            <h2>Supervisors</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>USN</th>
                    <th>Guide</th>
                    <th>Actions</th>
                </tr>
                <?php
                while ($row = $supervisors_result->fetch_assoc()) {
                    echo "<tr>
                          <!--  <td><a href='fetch_research_scholars.php?id=" . $row['id'] . "' class='hover-link'>" . htmlspecialchars($row['name']) . "</a></td> -->
                            <td><a href='fetch_research_scholars.php?supervisor_id=" . $row['id'] . "' class='hover-link'>" . htmlspecialchars($row['name']) . "</a></td>

                            <td>" . htmlspecialchars($row['usn']) . "</td>
                            <td>" . htmlspecialchars($row['guide']) . "</td>
                            <td>
                                <a href='edit_supervisor.php?id=" . $row['id'] . "'>Edit</a> | 
                                <a href='delete_supervisor.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this supervisor?\");'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </table>
        </div>

        <!-- Advisory Committee Section -->
        <div class="viewer-section">
            <h2>Advisory Committee</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Post</th>
                    <th>Actions</th>
                </tr>
                <?php
                while ($row = $advisory_committee_result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['post']) . "</td>
                            <td>
                                <a href='edit_advisory_committee.php?id=" . $row['id'] . "'>Edit</a> | 
                                <a href='delete_advisory_committee.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this advisory committee member?\");'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </table>
        </div>

    </div>

    <a href="index.php" class="button">Back to Home</a>
</body>
</html>
