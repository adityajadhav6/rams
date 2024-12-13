<?php
// Include any necessary PHP logic or database connection here
include 'db/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications - Research Centre Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
        }

        .top-section {
            height: 50vh; /* Half the viewport height */
            background-image: url('PHOTO-2024-12-14-00-37-25.jpg'); /* Replace with the path to your image */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: flex-end; /* Align text towards the bottom */
            color: white;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.7);
            padding-bottom: 20px; /* Adjust spacing from the bottom */
        }

        .top-section h1 {
            font-size: 3em;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-top: 20px;
            animation: fadeInDown 0.8s ease-in-out;
        }

        .tabs {
            display: flex;
            flex-direction: column; /* Stack buttons vertically */
            align-items: center;
            margin-top: 30px;
            animation: fadeIn 1s ease-in-out;
            padding: 0 10px;
        }

        .tab {
            padding: 20px 40px;
            margin: 10px 0;
            background: linear-gradient(to right, #007bff, #0056b3);
            color: #fff;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1.2em;
            font-weight: 500;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, background 0.3s;
            width: 180px;
        }

        .tab:hover {
            background: linear-gradient(to right, #0056b3, #004085);
            transform: translateY(-3px);
        }

        .tab.active {
            background: linear-gradient(to right, #004085, #00315a);
            font-weight: bold;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .content {
            margin: 40px auto;
            max-width: 800px;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            animation: fadeInUp 1s ease-in-out;
        }

        h2 {
            margin-top: 0;
            color: #007bff;
        }

        .back-button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }

        .back-button {
            display: inline-block;
            padding: 20px 40px;
            background: linear-gradient(to right, #6c757d, #5a6268);
            color: #fff;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1.5em;
            font-weight: 600;
            transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .back-button:hover {
            background: linear-gradient(to right, #5a6268, #495057);
            transform: translateY(-3px);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Top Section with Background Image -->
    <div class="top-section">
        <h1></h1>
    </div>

    <!-- Tabs Section -->
    <div class="tabs">
        <a href="publications_books.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) == 'publications_books.php' ? 'active' : ''; ?>">Books</a>
        <a href="publications_book_chapters.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) == 'publications_book_chapters.php' ? 'active' : ''; ?>">Book Chapters</a>
        <a href="publications_journals.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) == 'publications_journals.php' ? 'active' : ''; ?>">Journals</a>
        <a href="publications_conferences.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) == 'publications_conferences.php' ? 'active' : ''; ?>">Conferences</a>
    </div>

    <!-- Content Section -->
    <div class="content">
        <p>Welcome to the Research Centre Management System. Select a category above to manage or view publications.</p>
    </div>

    <!-- Back Button Centered -->
    <div class="back-button-container">
        <a href="index.php" class="back-button">Back to Home</a>
    </div>
</body>
</html>
