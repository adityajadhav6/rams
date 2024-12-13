<?php
session_start();
include 'db/connection.php'; // Database connection file

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Fetch all supervisors
$supervisors_query = "SELECT id, name FROM supervisors";
$supervisors_result = $conn->query($supervisors_query);

// Fetch advisory committee
$advisory_query = "SELECT name, post FROM advisory_committee";
$advisory_result = $conn->query($advisory_query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Research Centre</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        h1, h2, h3 {
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
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
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .back-button {
            margin-top: 20px;
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
        .no-data {
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Research Centre</h1>

    <!-- Supervisors and Scholars -->
    <section>
        <h2>Supervisors and Scholars</h2>
        <p>Click on a supervisor's name to view the list of their scholars.</p>
        <table>
            <thead>
            <tr>
                <th>Supervisor Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($supervisors_result->num_rows > 0): ?>
                <?php while ($row = $supervisors_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td>
                            <a href="scholars.php?supervisor_id=<?= $row['id']; ?>" class="button">View Scholars</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="no-data">No supervisors found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>

    <?php
    // Display scholars list if a supervisor is selected
    if (isset($_GET['supervisor_id'])) {
        $supervisor_id = intval($_GET['supervisor_id']);
        $scholars_query = "SELECT scholar_name, phd_status FROM research_scholars WHERE supervisor_id = ?";
        $stmt = $conn->prepare($scholars_query);
        $stmt->bind_param("i", $supervisor_id);
        $stmt->execute();
        $scholars_result = $stmt->get_result();
        ?>

        <section>
            <h3>Scholars List for Supervisor</h3>
            <table>
                <thead>
                <tr>
                    <th>Scholar Name</th>
                    <th>PhD Status</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($scholars_result->num_rows > 0): ?>
                    <?php while ($row = $scholars_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['scholar_name']); ?></td>
                            <td><?= htmlspecialchars($row['phd_status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="no-data">No scholars found for this supervisor</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <a href="scholars.php" class="back-button">Back</a>
        </section>
    <?php } ?>

    <!-- Advisory Committee -->
    <section>
        <h2>Advisory Committee</h2>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Post</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($advisory_result->num_rows > 0): ?>
                <?php while ($row = $advisory_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['post']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="no-data">No advisory committee members found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <a href="index.php" class="back-button">Back</a>
    </section>
</div>
</body>
</html>
