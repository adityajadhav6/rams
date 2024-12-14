<?php
include 'db/connection.php';

if (isset($_GET['supervisor_id']) && is_numeric($_GET['supervisor_id'])) {
    $supervisorId = intval($_GET['supervisor_id']);
    $supervisorQuery = "SELECT name FROM supervisors WHERE id = ?";
    $stmtSupervisor = $conn->prepare($supervisorQuery);
    $stmtSupervisor->bind_param("i", $supervisorId);
    $stmtSupervisor->execute();
    $supervisorResult = $stmtSupervisor->get_result();

    if ($supervisorResult && $supervisorResult->num_rows > 0) {
        $supervisor = $supervisorResult->fetch_assoc();
    } else {
        header("Refresh: 3; URL=supervisor.php");
        exit();
    }

    $query = "SELECT * FROM research_scholars WHERE supervisor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $supervisorId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    header("Location: supervisor.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Scholars</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            text-align: center;
            font-size: 1.5em;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: white;
            text-align: left;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #e9ecef;
        }
        form input, form select, form button {
            margin-bottom: 15px;
            padding: 10px;
            width: calc(100% - 20px);
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            background-color: #28a745;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }
        form button:hover {
            background-color: #218838;
        }
        .card {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .card h3 {
            margin: 0 0 10px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="header">
        Research Scholars for Supervisor: <?= htmlspecialchars($supervisor['name'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <div class="container">
        <div class="card">
            <h3>Research Scholars</h3>
            <?php if ($result && $result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Scholar Name</th>
                            <th>Ph.D Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['scholar_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['phd_status'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <a href="edit_research_scholar.php?id=<?= intval($row['id']) ?>" class="button">Edit</a>
                                    <a href="delete_research_scholar.php?id=<?= intval($row['id']) ?>" class="button" style="background-color: #dc3545;" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No research scholars found for this supervisor.</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3>Add New Research Scholar</h3>
            <form action="save_research_scholar.php" method="POST">
                <input type="hidden" name="supervisor_id" value="<?= $supervisorId ?>">
                <input type="text" name="research_scholar_name" placeholder="Scholar Name" required>
                <select name="phd_status" required>
                    <option value="Completed">Completed</option>
                    <option value="Thesis Submitted">Thesis Submitted</option>
                    <option value="Course Work Completed">Course Work Completed</option>
                    <option value="Applied for Course Work">Applied for Course Work</option>
                    <option value="Registered">Registered</option>
                </select>
                <button type="submit">Add Scholar</button>
            </form>
        </div>
        <div style="text-align: center;">
            <a href="supervisor.php" class="button" style="background-color: #6c757d;">Back to Supervisors</a>
        </div>
    </div>
</body>
</html>
