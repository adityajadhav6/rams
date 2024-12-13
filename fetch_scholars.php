<?php
include 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supervisorId = intval($_POST['supervisor_id']);

    $query = "SELECT name FROM scholars WHERE supervisor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $supervisorId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<ul class='list-group'>";
        while ($row = $result->fetch_assoc()) {
            echo "<li class='list-group-item'>" . htmlspecialchars($row['name']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No scholars found for this supervisor.</p>";
    }
    $stmt->close();
}
?>
