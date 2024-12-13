<?php
session_start();
include 'db/connection.php'; // Ensure this includes your database connection

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Check if the user is HOD or Admin
$is_hod = isset($_SESSION['user_type']) && ($_SESSION['user_type'] === 'hod' || $_SESSION['user_type'] === 'admin');

if (!$is_hod) {
    echo "Access denied!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Head of Research Center Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
            color: #333;
            animation: fadeIn 1.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .container { margin-top: 20px; }
        h1, h2, h3 { text-align: center; color: #2c3e50; margin-bottom: 20px; animation: fadeIn 1.5s; }
        .table { background-color: #fff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 40px; }
        .table thead th { background-color: #4CAF50; color: white; }
        .table tbody tr:hover { background-color: #f1f8ff; }
        .btn-back { background-color: #4CAF50; color: white; border-radius: 8px; padding: 10px 20px; text-decoration: none; }
        .btn-back:hover { background-color: #388E3C; }
        .table-container { overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Head of Research Center Management</h1>
        <a href="index.php" class="btn-back mb-4">Back to Dashboard</a>

        <!-- Research Center Section -->
        <h2>Research Center</h2>

        <!-- Supervisors Section -->
        <div class="table-container">
            <h3>Supervisors</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>USN</th>
                        <th>Guide</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM supervisors");
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td><a href='fetch_research_scholars.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</a></td>
                                    <td>" . htmlspecialchars($row['usn']) . "</td>
                                    <td>" . htmlspecialchars($row['guide']) . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No supervisors found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Advisory Committee Section -->
        <div class="table-container">
            <h3>Advisory Committee</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Post</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM advisory_committee");
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['post']) . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='text-center'>No advisory committee members found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Publications Section -->
        <h2>Publications</h2>

        <!-- Books -->
        <div class="table-container">
            <h3>Books</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Teacher Name</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Year</th>
                        <th>ISBN</th>
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
                                    <td>" . htmlspecialchars($row['author'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['publisher']) . "</td>
                                    <td>" . htmlspecialchars($row['year']) . "</td>
                                    <td>" . htmlspecialchars($row['isbn'] ?? 'N/A') . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No books found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Book Chapters -->
        <div class="table-container">
            <h3>Book Chapters</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Year</th>
                        <th>ISBN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM book_chapters");
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['title']) . "</td>
                                    <td>" . htmlspecialchars($row['author'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['publisher']) . "</td>
                                    <td>" . htmlspecialchars($row['year']) . "</td>
                                    <td>" . htmlspecialchars($row['isbn'] ?? 'N/A') . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No book chapters found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Journals -->
        <div class="table-container">
            <h3>Journals</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Year</th>
                        <th>ISSN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM journals");
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['title'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['author'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['publisher'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['year_of_publication'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['issn'] ?? 'N/A') . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No journals found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Conferences -->
        <div class="table-container">
            <h3>Conferences</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Details</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM conferences");
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['title']) . "</td>
                                    <td>" . htmlspecialchars($row['author'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['details'] ?? 'N/A') . "</td>
                                    <td>" . htmlspecialchars($row['year_of_publication']) . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No conferences found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

<!-- Patents Section -->
<h2>Patents</h2>

<!-- Published Patents -->
<div class="table-container">
    <h3>Published Patents</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Teacher Name</th>
                <th>Department</th>
                <th>Title</th>
                <th>Application No</th>
                <th>Year</th>
                <th>Status</th>
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
                            <td>" . htmlspecialchars($row['status']) . "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No published patents found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Granted Patents -->
<div class="table-container">
    <h3>Granted Patents</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Teacher Name</th>
                <th>Department</th>
                <th>Title</th>
                <th>Application No</th>
                <th>Year</th>
                <th>Status</th>
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
                            <td>" . htmlspecialchars($row['status']) . "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No granted patents found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>





            
                          <!-- Grants Section -->
<h2>Grants Received</h2>
<!-- Faculty Grants -->
<div class="table-container">
    <h3>Faculty Grants</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Grant Title</th>
                <th>Amount</th>
                <th>Year</th>
                <th>Principal Investigator</th>
                <th>Department</th>
                <th>Funding Agency</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query to get faculty grants from the grants table
            $result = $conn->query("SELECT * FROM grants WHERE type = 'faculty'");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['grant_title'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['amount_sanctioned'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['year_of_award'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['principal_investigator'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['department'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['funding_agency'] ?? 'N/A') . "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No faculty grants found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<!-- Student Grants -->
<div class="table-container">
    <h3>Student Grants</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Amount</th>
                <th>Year</th>
                <th>Student Name</th>
                <th>Department</th>
                <th>Funding Agency</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query to get student grants from the student_grants table
            $result = $conn->query("SELECT * FROM student_grants");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['project_name'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['amount_sanctioned'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['year_of_award'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['student_name'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['department'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['funding_agency'] ?? 'N/A') . "</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No student grants found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
