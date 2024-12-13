<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Check user type
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
$is_viewer = $user_type === 'viewer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Centre Management System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: linear-gradient(to right, #1a237e, #0d47a1);
            font-family: 'Arial', sans-serif;
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .container {
            text-align: center;
            background: linear-gradient(135deg, #bbdefb, #90caf9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            animation: slideIn 0.8s ease-in-out, pulseGlow 2s infinite;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulseGlow {
            0% {
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            }
            50% {
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            }
            100% {
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            }
        }

        h1 {
            margin-bottom: 30px;
            font-size: 2.5em;
            color: #006400;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .button {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 350px;
            height: 60px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            text-decoration: none;
            border-radius: 12px;
            font-size: 1.2em;
            font-weight: bold;
            transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }

        .button:hover {
            background: linear-gradient(135deg, #2a5298, #1e3c72);
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
        }

        .button:active {
            transform: translateY(2px);
        }

        .disabled {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }

        .logout-button {
            background: linear-gradient(135deg, #ff4b1f, #ff9068);
        }

        .logout-button:hover {
            background: linear-gradient(135deg, #ff9068, #ff4b1f);
        }

        .sub-buttons {
            display: none;
            flex-direction: column;
            gap: 15px;
            margin-top: 10px;
        }

        .sub-buttons.active {
            display: flex;
        }

        .sub-buttons a {
            width: 80%;
            max-width: 300px;
            margin: 0 auto;
            font-size: 1.1em;
            background: linear-gradient(135deg, #36d1dc, #5b86e5);
            color: white;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .sub-buttons a:hover {
            background: linear-gradient(135deg, #5b86e5, #36d1dc);
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 768px) {
            .button {
                width: 100%;
                height: auto;
                font-size: 1em;
                padding: 10px;
            }

            h1 {
                font-size: 1.8em;
            }

            .sub-buttons a {
                font-size: 1em;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Research Centre Management System<br>PESITM, Shivamogga</h1>
        
        <!-- Main Buttons Section -->
        <div class="buttons">
            <!-- Research Centre Button -->
            <a href="#" class="button" onclick="toggleButtons()">Research Centre</a>
            
            <!-- Sub Buttons for Supervisor, Scholars, and HOD -->
            <div id="sub-buttons" class="sub-buttons">
                <a href="supervisor.php" class="button <?php echo $is_viewer ? 'disabled' : ''; ?>">Supervisor</a>
                <a href="Scholars.php" class="button">Scholars</a>
                <a href="hod.php" class="button <?php echo $is_viewer ? 'disabled' : ''; ?>">Head Of Research Center Management</a>
            </div>

            <!-- Other Main Buttons -->
            <a href="publications.php" class="button">Publications</a>
            <a href="patents.php" class="button">Patents</a>
            <a href="grants.php" class="button">Grants Received</a>
            <a href="logout.php" class="button logout-button">Logout</a>
        </div>
    </div>

    <script>
        function toggleButtons() {
            const subButtons = document.getElementById('sub-buttons');
            subButtons.classList.toggle('active');
        }
    </script>
</body>
</html>
