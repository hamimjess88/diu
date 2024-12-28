<?php
session_start();

// Redirect to login.php if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIU Academic Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
    /* General Styles */
    body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #004d40, #009688);
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #ffffff;
            border-bottom: 2px solid #ddd;
        }

        .header img {
            width: 100px;
        }

        .header h1 {
            font-size: 1.8rem;
            color: #333;
            margin: 0;
        }

        .logout-button {
            background: #f44336;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .logout-button:hover {
            background: #d32f2f;
        }

        .menu {
            display: flex;
            justify-content: center;
            background: #00695c;
            padding: 10px;
        }

        .menu a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            margin: 0 15px;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .menu a:hover {
            background: #004d40;
            transform: translateY(-3px);
        }

        .menu a:hover {
            background: #004d40;
            transform: translateY(-3px);
        }

        .welcome-section {
            text-align: center;
            padding: 13px;
        }

        .welcome-section h2 {
            font-size: 2rem;
            color: #00695c;
            margin-bottom: 15px;
        }

        .welcome-section p {
            font-size: 1.2rem;
            color: #555;
        }

        .features {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .feature-card {
            flex: 1 1 calc(45% - 20px);
            max-width: 45%;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .feature-card svg {
            width: 60px;
            height: 60px;
            fill: #2575fc;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            font-size: 1.6rem;
            color: #004d40;
        }

        .feature-card p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 20px;
        }

        .feature-card button {
            background: #2575fc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .feature-card button:hover {
            background: #6a11cb;
        }

        .footer {
    text-align: center;
    padding: 15px;
    background: #004d40;
    color: white;
    font-size: 0.9rem;
    border-radius: 0 0 12px 12px; /* Rounded corners at the bottom */
    box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
    position: relative;
    bottom: 0;
    width: 100%;
}



/* Header Section */
.header {
            text-align: center;
            padding: 20px 20px;
            background: #ffffff;
            color: #00695c;
        }
        .header img {
            width: 120px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 2rem;
        }





      
        
        /* About Content Section */
        .about-content {
            padding: 30px;
            line-height: 1.8;
            color: #555;
        }
        .about-content h2 {
            font-size: 1.8rem;
            color: #004d40;
            margin-bottom: 20px;
            text-align: center;
        }
        .about-content p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .about-content .developer-section {
            margin-top: 30px;
            text-align: center;
        }
        .developer-section h3 {
            font-size: 1.5rem;
            color: #00695c;
            margin-bottom: 15px;
        }
        .developer-section p {
            color: #444;
            font-size: 1rem;
        }

        /* Footer Section */
        .footer {
            background: #004d40;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 0.9rem;
        }




    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://daffodilvarsity.edu.bd/template/images/diulogoside.png" alt="DIU Logo">
            <h1>Welcome, <?= $username ?>!</h1>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>

        <div class="menu">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="semester_result.php"> Results</a>
            <a href="totalcgpa.php"> Calculator</a>
        </div>

        <div class="welcome-section">
            <h2>Welcome to the DIU Academic Portal</h2>
            <p>Your one-stop solution for academic results, GPA calculations, and more!</p>
        </div>

        <div class="features">
            <div class="feature-card">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"/>
                    <path d="M12 7v5l3 3 .707-.707-2.707-2.707V7h-1z"/>
                </svg>
                <h3>Semester Results</h3>
                <p>View detailed semester results and analyze your performance.</p>
                <button onclick="location.href='semester_result.php';">View Results</button>
            </div>
            <div class="feature-card">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm-8 14h-4v-4h4v4zm0-6h-4V7h4v4zm6 6h-4v-4h4v4zm0-6h-4V7h4v4z"/>
                </svg>
                <h3>CGPA Calculator</h3>
                <p>Calculate your cumulative GPA across multiple semesters.</p>
                <button onclick="location.href='totalcgpa.php';">Calculate CGPA</button>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; <?= date('Y') ?> Daffodil International University. Developed by Tahsin Hamim. All Rights Reserved.
    </div>
</body>
</html>
