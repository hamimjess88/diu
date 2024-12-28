<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - DIU Portal</title>
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

        /* Menu Section */
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
    <!-- Header Section -->
    <div class="header">
        <img src="https://daffodilvarsity.edu.bd/template/images/diulogoside.png" alt="DIU Logo">
        <h1>About the DIU Academic Portal</h1>
    </div>

    <!-- Menu Section -->
    <div class="menu">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="semester_result.php"> Results</a>
            <a href="totalcgpa.php"> Calculator</a>
        </div>

    <!-- About Content Section -->
    <div class="about-content">
        <h2>About the Portal</h2>
        <p>
            The DIU Academic Portal is a modern solution for students of Daffodil International University. It provides 
            easy access to semester results and facilitates cumulative GPA calculations in a user-friendly manner.
        </p>
        <p>
            Designed with simplicity and efficiency in mind, the portal is fully responsive, ensuring accessibility from 
            any device. Our goal is to make academic management seamless and hassle-free for students.
        </p>

        <!-- Developer Section -->
        <div class="developer-section">
            <h3>Meet the Developer</h3>
            <p>
                This portal was proudly developed by <b>Tahsin Hamim</b>. Combining creativity with functionality, 
                the developer has ensured a smooth user experience for all DIU students.
            </p>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        &copy; <?= date('Y') ?> Daffodil International University. Developed by Tahsin Hamim.
    </div>
</div>

</body>
</html>
