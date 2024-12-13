<?php
// Include the database connection file
include 'db/connection.php';

// Start the session
session_start();

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['viewer_login'])) {
        // Viewer login - No password required
        $_SESSION['logged_in'] = true;
        $_SESSION['user_type'] = 'viewer';

        // Redirect to index.php
        header('Location: index.php');
        exit();
    }

    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = md5(trim($_POST['password'])); // Using MD5 hashing for password

        if (!empty($username) && !empty($password)) {
            // Validate against database
            $query = "SELECT * FROM users WHERE username = ? AND password = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Successful login
                $_SESSION['logged_in'] = true;
                $_SESSION['user_type'] = 'admin'; // Set user type as admin
                header('Location: index.php');
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Please fill in both fields.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PESITM CSE Research Centre Management System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #ADD8E6;
            font-family: 'Roboto', sans-serif;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: slideIn 0.8s ease-in-out;
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

        h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #1e3c72;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1em;
        }

        .button {
            padding: 10px 20px;
            background-color: #1e90ff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
            display: inline-block;
            margin: 5px;
            transition: background-color 0.3s, transform 0.3s;
            border: none;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .error-message {
            color: #e74c3c;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PESITM CSE, Research Centre Management System</h1>

        <!-- Display error message if any -->
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Admin login form -->
        <form method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="button">Login as Admin</button>
        </form>

        <!-- Viewer login button -->
        <form method="post" style="margin-top: 20px;">
            <button type="submit" name="viewer_login" class="button">Login as Viewer</button>
        </form>
    </div>
</body>
</html>