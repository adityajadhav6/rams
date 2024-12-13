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

// Initialize filter variables
$search_title = isset($_POST['search_title']) ? $_POST['search_title'] : '';
$search_investigator = isset($_POST['search_investigator']) ? $_POST['search_investigator'] : '';
$search_year = isset($_POST['search_year']) ? $_POST['search_year'] : '';

// Construct SQL query with filters
$query = "SELECT * FROM grants WHERE 1";
if ($search_title) {
    $query .= " AND grant_title LIKE ?";
}
if ($search_investigator) {
    $query .= " AND principal_investigator LIKE ?";
}
if ($search_year) {
    $query .= " AND year_of_award = ?";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Grants</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f7fb;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
            padding: 40px 60px;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            color: #333;
            margin-bottom: 30px;
        }

        .form-container {
            margin-bottom: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .form-container input, .form-container button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .form-container input:focus, .form-container button:focus {
            outline: none;
            border-color: #007bff;
        }

        .form-container button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
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

        .search-bar {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 30px;
        }

        .search-bar input {
            width: 30%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .search-bar button {
            width: 15%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 1rem;
            color: #333;
        }

        table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table td a {
            text-decoration: none;
            color: white;
            background-color: #28a745;
            padding: 8px 16px;
            border-radius: 5px;
            margin-right: 5px;
            font-size: 0.9rem;
        }

        table td a.danger {
            background-color: #dc3545;
        }

        table td a:hover {
            background-color: #0056b3;
        }

        .back-button {
            display: inline-block;
            background-color: #6c757d;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2rem;
            text-align: center;
            transition: background-color 0.3s ease;
            margin-bottom: 30px;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            h1 {
                font-size: 2em;
            }

            .form-container input, .form-container button {
                font-size: 0.9rem;
                padding: 10px;
            }

            .search-bar input {
                width: 45%;
            }

            .search-bar button {
                width: 40%;
            }

            .back-button {
                width: 100%;
                padding: 15px;
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Back Button -->
    <a href="grants.php" class="back-button">Back to Grants</a>

    <h1>Faculty Grants</h1>

    <!-- Search Filter Form -->
    <div class="search-bar">
        <form method="POST" action="">
            <input type="text" name="search_title" placeholder="Search by Grant Title" value="<?php echo htmlspecialchars($search_title); ?>">
            <input type="text" name="search_investigator" placeholder="Search by Principal Investigator" value="<?php echo htmlspecialchars($search_investigator); ?>">
            <input type="number" name="search_year" placeholder="Search by Year" value="<?php echo htmlspecialchars($search_year); ?>" min="1900" max="<?php echo date('Y'); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php if (!$is_viewer): ?>
        <div class="form-container">
            <form method="post" action="save_faculty_grant.php">
                <input type="text" name="grant_title" placeholder="Grant Title" required>
                <input type="text" name="principal_investigator" placeholder="Principal Investigator" required>
                <input type="text" name="department" placeholder="Department" required>
                <input type="number" name="year_of_award" placeholder="Year of Award" min="1900" max="<?php echo date('Y'); ?>" required>
                <input type="number" name="amount_sanctioned" placeholder="Amount Sanctioned (in Lakhs)" min="0" step="0.01" required>
                <input type="text" name="duration" placeholder="Duration of the Project" required>
                <input type="text" name="funding_agency" placeholder="Funding Agency" required>
                <button type="submit">Save Grant</button>
            </form>
        </div>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>Grant Title</th>
            <th>Principal Investigator</th>
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
        // Prepare SQL statement with filters
        if ($stmt = $conn->prepare($query)) {
            // Bind parameters to avoid SQL injection
            $params = [];
            if ($search_title) {
                $params[] = '%' . $search_title . '%';
            }
            if ($search_investigator) {
                $params[] = '%' . $search_investigator . '%';
            }
            if ($search_year) {
                $params[] = $search_year;
            }

            if ($params) {
                $stmt->bind_param(str_repeat('s', count($params)), ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['grant_title']) . "</td>
                            <td>" . htmlspecialchars($row['principal_investigator']) . "</td>
                            <td>" . htmlspecialchars($row['department']) . "</td>
                            <td>" . htmlspecialchars($row['year_of_award']) . "</td>
                            <td>" . htmlspecialchars($row['amount_sanctioned']) . "</td>
                            <td>" . htmlspecialchars($row['duration']) . "</td>
                            <td>" . htmlspecialchars($row['funding_agency']) . "</td>";
                    if (!$is_viewer) {
                        echo "<td>
                                <a href='edit_grant.php?id=" . $row['id'] . "' class='button'>Edit</a>
                                <a href='delete_grant.php?id=" . $row['id'] . "' class='button danger'>Delete</a>
                              </td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align: center;'>No records found</td></tr>";
            }

            $stmt->close();
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
