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
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-top: 20px;
            animation: fadeInDown 0.8s ease-in-out;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            animation: fadeIn 1s ease-in-out;
        }

        .tab {
            padding: 15px 30px;
            margin: 0 10px;
            background: linear-gradient(to right, #007bff, #0056b3);
            color: #fff;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, background 0.3s;
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

        .back-button {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 20px;
            background: linear-gradient(to right, #6c757d, #5a6268);
            color: #fff;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1em;
            font-weight: 500;
            transition: background 0.3s, transform 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    <h1>Publications</h1>
    <div class="tabs">
        <a href="publications_books.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) == 'publications_books.php' ? 'active' : ''; ?>">Books</a>
        <a href="publications_book_chapters.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) == 'publications_book_chapters.php' ? 'active' : ''; ?>">Book Chapters</a>
        <a href="publications_journals.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) == 'publications_journals.php' ? 'active' : ''; ?>">Journals</a>
        <a href="publications_conferences.php" class="tab <?php echo basename($_SERVER['PHP_SELF']) == 'publications_conferences.php' ? 'active' : ''; ?>">Conferences</a>
    </div>
    <div class="content">
        <p>Welcome to the Research Centre Management System. Select a category above to manage or view publications.</p>
    </div>
    <div class="text-center">
        <a href="index.php" class="back-button">Back to Home</a>
    </div>
</body>
</html>
