<?php
session_start();
include 'db/connection.php'; // Ensure your database connection file is correct

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Check if the user is a viewer
$is_viewer = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'viewer';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Grants</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .form-container {
            margin-bottom: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container input, .form-container button {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            font-size: 1rem;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .danger {
            background-color: #dc3545;
        }

        .danger:hover {
            background-color: #a71d2a;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Student Grants</h1>

    <?php if (!$is_viewer): ?>
        <div class="form-container">
            <form method="post" action="save_student_grant.php">
                <input type="text" name="project_name" placeholder="Project Name" required>
                <input type="text" name="student_name" placeholder="Student Name" required>
                <input type="text" name="department" placeholder="Department" required>
                <input type="number" name="year_of_award" placeholder="Year of Award" min="1900" max="<?php echo date('Y'); ?>" required>
                <input type="number" name="amount" placeholder="Amount Sanctioned (in Lakhs)" min="0" step="0.01" required>
                <input type="text" name="duration" placeholder="Duration of the Project" required>
                <input type="text" name="funding_agency" placeholder="Funding Agency" required>
                <button type="submit" class="button">Save Grant</button>
            </form>
        </div>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>Project Name</th>
            <th>Student Name</th>
            <th>Department</th>
            <th>Year of Award</th>
            <th>Amount (Lakhs)</th>
            <th>Duration</th>
            <th>Funding Agency</th>
            <?php if (!$is_viewer): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php
        // Fetch student grants from the database using a prepared statement
        $query = "SELECT * FROM student_grants";
        if ($stmt = $conn->prepare($query)) {
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['project_name']) . "</td>
                            <td>" . htmlspecialchars($row['student_name']) . "</td>
                            <td>" . htmlspecialchars($row['department']) . "</td>
                            <td>" . htmlspecialchars($row['year_of_award']) . "</td>
                            <td>" . htmlspecialchars($row['amount_sanctioned']) . "</td>
                            <td>" . htmlspecialchars($row['duration']) . "</td>
                            <td>" . htmlspecialchars($row['funding_agency']) . "</td>";
                    if (!$is_viewer) {
                        echo "<td>
                                <a href='edit_student_grant.php?id=" . $row['id'] . "' class='button'>Edit</a>
                                <a href='delete_student_grant.php?id=" . $row['id'] . "' class='button danger'>Delete</a>
                              </td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align: center;'>No records found</td></tr>";
            }
            $stmt->close();
        } else {
            echo "<tr><td colspan='8' style='text-align: center;'>Error fetching data: " . $conn->error . "</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <a href="grants.php" class="button">Back</a>
    </div>
</div>
</body>
</html>