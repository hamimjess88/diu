<?php
session_start();

// Redirect to login.php if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username']);

// API to fetch semester list
$semesterApiUrl = "http://localhost/diu/apisocket/semesterListapi.php";
$semesterMap = [];

// Fetch semester list from API
try {
    $semesterResponse = file_get_contents($semesterApiUrl);
    if ($semesterResponse === false) {
        $error = "Failed to fetch semester list from API.";
    } else {
        $semesterMap = json_decode($semesterResponse, true);
        if ($semesterMap === null || json_last_error() !== JSON_ERROR_NONE) {
            $error = "Error decoding semester list from API: " . json_last_error_msg();
            $semesterMap = [];
        }
    }
} catch (Exception $e) {
    $error = "Error fetching semester list: " . $e->getMessage();
    $semesterMap = [];
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = htmlspecialchars(trim($_POST['studentId']));
    $semesterId = htmlspecialchars(trim($_POST['semesterId']));

    // Validate input fields
    if (empty($studentId) || empty($semesterId)) {
        $error = "Please provide both Student ID and Semester.";
    } else {
        // API URL for student results
        $apiUrl = "http://localhost/diuapi.php/?studentId=$studentId&semesterId=$semesterId";

        // Fetch data from the API
        $response = file_get_contents($apiUrl);
        if ($response === false) {
            $error = "Failed to connect to the API. Please check the server.";
        } else {
            // Decode the JSON response
            $data = json_decode($response, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                $error = "Error decoding API response: " . json_last_error_msg();
            } else {
                $studentInfo = $data['studentInfo'] ?? [];
                $semesterResults = $data['semesterResult'] ?? [];

                // Calculate SGPA
                $totalCredits = 0;
                $totalGradePoints = 0;

                foreach ($semesterResults as $result) {
                    $credit = $result['totalCredit'];
                    $gradePoint = $result['pointEquivalent'];
                    $totalCredits += $credit;
                    $totalGradePoints += $credit * $gradePoint;
                }

                $sgpa = $totalCredits > 0 ? round($totalGradePoints / $totalCredits, 2) : 0;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester Results - DIU Academic Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #004d40, #009688);
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
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

        .form-section, .result-section {
            padding: 20px;
        }

        .form-section form {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .form-section input, .form-section select {
            padding: 10px;
            font-size: 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .form-section button {
            background: #2575fc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .form-section button:hover {
            background: #6a11cb;
        }

        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
        }

        .result-table th, .result-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .result-table th {
            background: #6a11cb;
            color: white;
        }

        .sgpa-section {
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background: #004d40;
            color: white;
            font-size: 0.9rem;
            border-radius: 0 0 12px 12px;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                color: black;
            }

            .header, .menu, .form-section, .footer {
                display: none;
            }

            .container {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
            }

            .result-section {
                margin: 0;
                padding: 0;
            }
        }

        .print-button {
            display: block;
            margin: 20px auto;
            background: #2575fc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .print-button:hover {
            background: #6a11cb;
        }
    </style>
    <script>
        function printResults() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="https://daffodilvarsity.edu.bd/template/images/diulogoside.png" alt="DIU Logo">
            <h1>Welcome, <?= $username ?>!</h1>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>

        <!-- Menu -->
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="semester_result.php"> Results</a>
            <a href="totalcgpa.php"> Calculator</a>
        </div>


        <!-- Form Section -->
        <div class="form-section">
            <form method="POST">
                <input type="text" name="studentId" placeholder="Enter Student ID" required>
                <select name="semesterId" required>
                    <option value="">Select Semester</option>
                    <?php foreach ($semesterMap as $semester): ?>
                        <option value="<?= htmlspecialchars($semester['semesterId']) ?>">
                            <?= htmlspecialchars($semester['semesterName'] . " " . $semester['semesterYear']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Get Results</button>
            </form>
        </div>

        <!-- Results -->
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?= $error ?></p>
        <?php endif; ?>

        <?php if (isset($studentInfo) && !empty($studentInfo)): ?>
            <div class="result-section">
                <h2>Student Information</h2>
                <table class="result-table">
                    <tr><th>Student ID</th><td><?= htmlspecialchars($studentInfo['studentId']) ?></td></tr>
                    <tr><th>Name</th><td><?= htmlspecialchars($studentInfo['studentName']) ?></td></tr>
                    <tr><th>Program</th><td><?= htmlspecialchars($studentInfo['programName']) ?></td></tr>
                    <tr><th>Faculty</th><td><?= htmlspecialchars($studentInfo['facultyName']) ?></td></tr>
                    <tr><th>Batch</th><td><?= htmlspecialchars($studentInfo['batchNo']) ?></td></tr>
                </table>
            </div>
        <?php endif; ?>

        <?php if (isset($semesterResults) && !empty($semesterResults)): ?>
            <div class="result-section">
                <h2>Semester Results</h2>
                <table class="result-table">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Title</th>
                            <th>Credit</th>
                            <th>Grade</th>
                            <th>Grade Point</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($semesterResults as $result): ?>
                            <tr>
                                <td><?= htmlspecialchars($result['customCourseId']) ?></td>
                                <td><?= htmlspecialchars($result['courseTitle']) ?></td>
                                <td><?= htmlspecialchars($result['totalCredit']) ?></td>
                                <td><?= htmlspecialchars($result['gradeLetter']) ?></td>
                                <td><?= htmlspecialchars($result['pointEquivalent']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="sgpa-section">SGPA: <?= $sgpa ?></div>
            </div>
            <button class="print-button" onclick="printResults()">Print Results</button>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; <?= date('Y') ?> Daffodil International University. Developed by Tahsin Hamim. All rights reserved.
    </div>
</body>
</html>
