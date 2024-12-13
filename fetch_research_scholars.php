<?php
include 'db/connection.php';

// Check if supervisor_id is passed in the URL
if (isset($_GET['supervisor_id']) && is_numeric($_GET['supervisor_id'])) {
    $supervisorId = intval($_GET['supervisor_id']); // Ensure supervisor ID is an integer

    // Fetch the supervisor's name
    $supervisorQuery = "SELECT name FROM supervisors WHERE id = ?";
    $stmtSupervisor = $conn->prepare($supervisorQuery);
    $stmtSupervisor->bind_param("i", $supervisorId);
    $stmtSupervisor->execute();
    $supervisorResult = $stmtSupervisor->get_result();

    if ($supervisorResult && $supervisorResult->num_rows > 0) {
        $supervisor = $supervisorResult->fetch_assoc();
        echo "<h2>Research Scholars for Supervisor: " . htmlspecialchars($supervisor['name'], ENT_QUOTES, 'UTF-8') . "</h2>";
    } else {
        echo "<p style='color: red;'>Invalid supervisor ID. Redirecting...</p>";
        header("Refresh: 3; URL=supervisor.php"); // Redirect after 3 seconds
        exit();
    }

    // Fetch the research scholars related to the selected supervisor
    $query = "SELECT * FROM research_scholars WHERE supervisor_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("<p style='color: red;'>Error preparing the query: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . "</p>");
    }

    $stmt->bind_param("i", $supervisorId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Back to Supervisor Page Button
    echo "<div style='margin-bottom: 20px; text-align: right;'>
            <a href='supervisor.php' style='text-decoration: none;'>
                <button style='
                    background-color: #007bff; 
                    color: white; 
                    padding: 10px 20px; 
                    border: none; 
                    border-radius: 5px; 
                    font-size: 16px; 
                    cursor: pointer; 
                    transition: background-color 0.3s ease;'
                    onmouseover=\"this.style.backgroundColor='#0056b3'\"
                    onmouseout=\"this.style.backgroundColor='#007bff'\"
                >
                    Back to Supervisors
                </button>
            </a>
          </div>";

    // Display the research scholars data in a table
    if ($result && $result->num_rows > 0) {
        echo "<table class='research-scholar-table' style='width: 100%; border-collapse: collapse;'>
                <tr style='background-color: #f2f2f2;'>
                    <th style='border: 1px solid #ddd; padding: 8px;'>Name of the Research Scholar</th>
                    <th style='border: 1px solid #ddd; padding: 8px;'>Ph.D Status</th>
                    <th style='border: 1px solid #ddd; padding: 8px;'>Actions</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($row['scholar_name'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td style='border: 1px solid #ddd; padding: 8px;'>" . htmlspecialchars($row['phd_status'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>
                        <a href='edit_research_scholar.php?id=" . intval($row['id']) . "' style='color: #007bff; text-decoration: none;'>Edit</a> | 
                        <a href='delete_research_scholar.php?id=" . intval($row['id']) . "' style='color: #dc3545; text-decoration: none;' onclick='return confirm(\"Are you sure you want to delete this scholar?\");'>Delete</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No research scholars found for this supervisor.</p>";
    }

    // Form to add a new scholar
    echo "<h3>Add a New Research Scholar</h3>";
    echo "<form action='save_research_scholar.php' method='POST' style='margin-top: 20px;'>
            <input type='hidden' name='supervisor_id' value='$supervisorId'>
            <input type='text' name='research_scholar_name' placeholder='Research Scholar Name' required style='padding: 8px; margin-bottom: 10px; width: calc(100% - 16px); border: 1px solid #ddd; border-radius: 5px;'>
            <select name='phd_status' required style='padding: 8px; margin-bottom: 10px; width: calc(100% - 16px); border: 1px solid #ddd; border-radius: 5px;'>
                <option value='Completed'>Completed</option>
                <option value='Thesis submitted'>Thesis submitted</option>
                <option value='Course Work Completed'>Course Work Completed</option>
                <option value='Applied for Course Work'>Applied for Course Work</option>
                <option value='Registered'>Registered</option>
            </select>
            <button type='submit' style='
                background-color: #28a745; 
                color: white; 
                padding: 10px 20px; 
                border: none; 
                border-radius: 5px; 
                font-size: 16px; 
                cursor: pointer; 
                transition: background-color 0.3s ease;'
                onmouseover=\"this.style.backgroundColor='#218838'\"
                onmouseout=\"this.style.backgroundColor='#28a745'\"
            >
                Add Research Scholar
            </button>
          </form>";
} else {
    header("Location:supervisor.php");
    exit();
}
?>
