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
$isViewer = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'viewer';

// Ensure the database connection is established
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$isViewer) {
    $teacher_name = $_POST['teacher_name'];
    $title = $_POST['title'];
    $national_international = $_POST['national_international'];
    $year = $_POST['year'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];

    // Prepare the SQL query to insert a new book
    $stmt = $conn->prepare("INSERT INTO books (teacher_name, title, national_international, year, isbn, publisher) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $teacher_name, $title, $national_international, $year, $isbn, $publisher);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Book added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error adding book: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books - Research Centre Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }

        h1, h2 {
            text-align: center;
            color: #007bff;
            animation: slideIn 1s ease-in-out;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }

        table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
            animation: fadeInUp 1s ease-in-out;
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
            animation: slideIn 1s ease-in-out;
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

        .alert {
            animation: fadeInDown 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Books</h1>

        <?php if (!$isViewer): ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="teacher_name">Name of the Teacher:</label>
                    <input type="text" id="teacher_name" name="teacher_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="title">Title of the Book:</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="national_international">National / International:</label>
                    <select id="national_international" name="national_international" class="form-control">
                        <option value="National">National</option>
                        <option value="International">International</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">Year of Publication:</label>
                    <input type="number" id="year" name="year" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN/ISSN Number:</label>
                    <input type="text" id="isbn" name="isbn" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="publisher">Name of the Publisher:</label>
                    <input type="text" id="publisher" name="publisher" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Save</button>
            </form>
        <?php else: ?>
            <p class="text-center text-muted">You have read-only access. You cannot add or modify records.</p>
        <?php endif; ?>

        <h2 class="mt-4">Existing Books</h2>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Teacher Name</th>
                    <th>Title</th>
                    <th>National/International</th>
                    <th>Year</th>
                    <th>ISBN/ISSN</th>
                    <th>Publisher</th>
                    <?php if (!$isViewer): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM books");
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['teacher_name']) . "</td>
                                <td>" . htmlspecialchars($row['title']) . "</td>
                                <td>" . htmlspecialchars($row['national_international']) . "</td>
                                <td>" . htmlspecialchars($row['year']) . "</td>
                                <td>" . htmlspecialchars($row['isbn']) . "</td>
                                <td>" . htmlspecialchars($row['publisher']) . "</td>";
                        if (!$isViewer) {
                            echo "<td>
                                    <a href='edit_book.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete_book.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                                  </td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . ($isViewer ? "6" : "7") . "' class='text-center'>No records found</td></tr>";
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