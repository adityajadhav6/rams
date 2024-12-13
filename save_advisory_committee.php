<?php
include 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $post = $_POST['post'];

    try {
        // Use INSERT...ON DUPLICATE KEY UPDATE to avoid duplicate key errors
        $query = "INSERT INTO advisory_committee (name, post) 
                  VALUES (?, ?)
                  ON DUPLICATE KEY UPDATE 
                      post = VALUES(post)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $name, $post);
        $stmt->execute();

        header("Location: Supervisor.php"); // Redirect to the admin panel
        exit();
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
