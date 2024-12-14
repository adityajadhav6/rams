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
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1, h2 {
            text-align: center;
            color: #444;
        }

        h1 {
            margin-top: 20px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-container, .viewer-section {
            margin-bottom: 30px;
        }

        .form-container h2 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }

        .form-container form input, 
        .form-container form select {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
        }

        .form-container form button {
            background-color: #007bff;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container form button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            overflow-x: auto;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .hover-link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .hover-link:hover {
            text-decoration: underline;
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            table {
                font-size: 0.9em;
            }

            .form-container form input, 
            .form-container form select {
                font-size: 0.9em;
            }

            .form-container form button {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <h1>Research Centre Management</h1>
    <div class="container">

        <!-- Add Supervisor Form -->
        <div class="form-container">
            <h2>Add Supervisor</h2>
            <form action="save_supervisor.php" method="POST">
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
                if ($supervisors_result && $supervisors_result->num_rows > 0) {
                    while ($row = $supervisors_result->fetch_assoc()) {
                        echo "<tr>
                                <td><a href='fetch_research_scholars.php?supervisor_id=" . $row['id'] . "' class='hover-link'>" . htmlspecialchars($row['name']) . "</a></td>
                                <td>" . htmlspecialchars($row['usn']) . "</td>
                                <td>" . htmlspecialchars($row['guide']) . "</td>
                                <td>
                                    <a href='edit_supervisor.php?id=" . $row['id'] . "' class='hover-link'>Edit</a> | 
                                    <a href='delete_supervisor.php?id=" . $row['id'] . "' class='hover-link' style='color: #dc3545;' onclick='return confirm(\"Are you sure you want to delete this supervisor?\");'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No Supervisors found</td></tr>";
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
                if ($advisory_committee_result && $advisory_committee_result->num_rows > 0) {
                    while ($row = $advisory_committee_result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['post']) . "</td>
                                <td>
                                    <a href='edit_advisory_committee.php?id=" . $row['id'] . "' class='hover-link'>Edit</a> | 
                                    <a href='delete_advisory_committee.php?id=" . $row['id'] . "' class='hover-link' style='color: #dc3545;' onclick='return confirm(\"Are you sure you want to delete this advisory committee member?\");'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No Advisory Committee Members found</td></tr>";
                }
                ?>
            </table>
        </div>

    </div>

    <a href="index.php" class="button">Back to Home</a>
</body>
</html>
