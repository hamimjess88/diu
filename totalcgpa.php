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
        $semesters = json_decode($semesterResponse, true);
        if ($semesters === null || json_last_error() !== JSON_ERROR_NONE) {
            $error = "Error decoding semester list from API: " . json_last_error_msg();
        } else {
            foreach ($semesters as $semester) {
                $semesterMap[$semester['semesterId']] = $semester['semesterName'] . ' ' . $semester['semesterYear'];
            }
        }
    }
} catch (Exception $e) {
    $error = "Error fetching semester list: " . $e->getMessage();
}

// Initialize variables
$studentName = $programName = $facultyName = $batchNo = 'Unknown';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = htmlspecialchars(trim($_POST['studentId']));
    $selectedSemesters = $_POST['semesters'] ?? [];

    // Validate input fields
    if (empty($studentId) || empty($selectedSemesters)) {
        $error = "Please provide your Student ID and select at least one semester.";
    } else {
        $allResults = [];
        $totalCredits = 0;
        $totalGradePoints = 0;

        foreach ($selectedSemesters as $semesterId) {
            $semesterName = $semesterMap[$semesterId] ?? 'Unknown Semester';
            $apiUrl = "http://localhost/diuapi.php/?studentId=$studentId&semesterId=$semesterId";

            // Fetch data from the API
            $response = file_get_contents($apiUrl);
            if ($response === false) {
                $error = "Failed to connect to the API for Semester $semesterName.";
                break;
            } else {
                $data = json_decode($response, true);
                if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                    $error = "Error decoding API response for Semester $semesterName: " . json_last_error_msg();
                    break;
                } else {
                    $semesterResults = $data['semesterResult'] ?? [];
                    $studentName = $data['studentInfo']['studentName'] ?? 'Unknown';
                    $programName = $data['studentInfo']['programName'] ?? 'Unknown';
                    $facultyName = $data['studentInfo']['facultyName'] ?? 'Unknown';
                    $batchNo = $data['studentInfo']['batchNo'] ?? 'Unknown';

                    foreach ($semesterResults as &$result) {
                        $result['semesterName'] = $semesterName; // Add semester name to each course
                    }
                    $allResults = array_merge($allResults, $semesterResults);

                    // Calculate total credits and grade points
                    foreach ($semesterResults as $result) {
                        $credit = $result['totalCredit'];
                        $gradePoint = $result['pointEquivalent'];
                        $totalCredits += $credit;
                        $totalGradePoints += $credit * $gradePoint;
                    }
                }
            }
        }

        // Calculate CGPA
        $cumulativeCgpa = $totalCredits > 0 ? round($totalGradePoints / $totalCredits, 2) : 0;
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
        /* CSS Code Integration */

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #004d40, #009688);
            color: #333;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius:  12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
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
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        .form-section input {
            padding: 10px;
            font-size: 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 300px;
        }
        .dropdown-container {
            position: relative;
            width: 300px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 15px;
        }
        .dropdown-header {
            padding: 10px 15px;
            font-size: 1rem;
            font-weight: 500;
            color: #333;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .dropdown-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
        }
        .dropdown-container.open .dropdown-options {
            display: block;
        }
        .dropdown-options label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .dropdown-options label:hover {
            background: #e0f7fa;
        }
        .dropdown-options input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #009688;
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
        .footer {
            text-align: center;
            padding: 15px;
            background: #004 d40;
            color: white;
            font-size: 0.9rem;
            border-radius: 0 0 12px 12px;
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
        @media print {
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


        .result-box {
    margin-top: 20px;
    text-align: center;
    padding: 20px;
    background: #f9f9f9; /* Light background for contrast */
    border-radius: 12px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

h2 {
    color: #00695c; /* Dark teal color for headings */
    margin-bottom: 20px; /* Space below headings */
}

.summary-table, .result-table {
    width: 100%;
    max-width: 800px; /* Limit the width for better readability */
    margin: 0 auto; /* Center the table */
    border-collapse: collapse; /* Remove space between borders */
    text-align: left; /* Align text to the left */
}

.summary-table th, .summary-table td, .result-table th, .result-table td {
    padding: 12px; /* Padding for table cells */
    border: 1px solid #ddd; /* Light border for table cells */
    font-size: 1rem; /* Font size for table text */
}

.summary-table th, .result-table th {
    background: #6a11cb; /* Purple background for header */
    color: white; /* White text for header */
    font-weight: bold; /* Bold text for header */
}

.summary-table td {
    background: #fff; /* White background for table data */
}

.cgpa-row {
    text-align: center;
    font-weight: bold;
    color: white; /* White text for better contrast */
    background: #ff5722; /* Bright background color to highlight */
    font-size: 1.5rem; /* Increase font size for emphasis */
    padding: 10px; /* Add padding for better spacing */
}

.result-table th {
    background: #6a11cb; /* Purple background for result table header */
    color: white; /* White text for result table header */
}

.result-table td {
    background: #f4f4f4; /* Light gray background for result table data */
}

.print-button {
    display: block;
    margin: 20px auto;
    background: #2575fc; /* Blue background for print button */
    color: white; /* White text for print button */
    padding: 10px 20px; /* Padding for print button */
    border: none; /* No border */
    border-radius: 8px; /* Rounded corners */
    font-size: 1rem; /* Font size for print button */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background 0.3s ease; /* Smooth background transition */
}

.print-button:hover {
    background: #6a11cb; /* Darker blue on hover */
}

        
        .cgpa-row {
    text-align: center;
    font-weight: bold;
    color: white; /* Change text color to white for better contrast */
    background: #ff5722; /* Bright background color to highlight */
    font-size: 1.5rem; /* Increase font size for emphasis */
    padding: 10px; /* Add padding for better spacing */
}

    </style>
    <script>
        function printResults() {
            const printContents = document.querySelector('.result-box').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownHeader = document.querySelector('.dropdown-header');
            const dropdownContainer = document.querySelector('.dropdown-container');

            dropdownHeader.addEventListener('click', function() {
                dropdownContainer.classList.toggle('open');
            });
        });
    </script>
</head>
<body>
<div class="container">
    <!-- Header Section -->
    <div class="header">
        <img src="https://daffodilvarsity.edu.bd/template/images/diulogoside.png" alt="DIU Logo">
        <h1>Welcome, <?= $username ?>!</h1>
        <form action="logout.php" method="POST">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>

    <!-- Menu Section -->
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
            <div class="dropdown-container">
                <div class="dropdown-header">
                    <span>Select Semesters</span>
                    <span>&#9662;</span>
                </div>
                <div class="dropdown-options">
                    <?php foreach ($semesterMap as $semesterId => $semesterName): ?>
                        <label>
                            <input type="checkbox" name="semesters[]" value="<?= htmlspecialchars($semesterId) ?>">
                            <?= htmlspecialchars($semesterName) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit">Calculate CGPA</button>
        </form>
    </div>

    <?php if (isset($error)): ?>
        <p class="error" style="text-align: center; color: red;"><?= $error ?></p>
    <?php endif; ?>

    <?php if (isset($cumulativeCgpa)): ?>
        <div class="result-box">
            <h2>Student Academic Summary</h2>
            <table class="summary-table">
                <tr>
                    <th>Name</th>
                    <td><?= htmlspecialchars($studentName) ?></td>
                </tr>
                <tr>
                    <th>ID</th>
                    <td><?= htmlspecialchars($studentId) ?></td>
                </tr>
                <tr>
                    <th>Program</th>
                    <td><?= htmlspecialchars($programName) ?></td>
                </tr>
                <tr>
                    <th>Faculty</th>
                    <td><?= htmlspecialchars($facultyName) ?></td>
                </tr>
                <tr>
                    <th>Batch</th>
                    <td><?= htmlspecialchars($batchNo) ?></td>
                </tr>
                <tr>
                    <th>Total Completed Credits</th>
                    <td><?= $totalCredits ?></td>
                </tr>
                <tr>
                    <th colspan="2" class="cgpa-row">Total CGPA: <?= $cumulativeCgpa ?></th>
                </tr>
            </table>

            <h2>Course Results Summary</h2>
            <table class="result-table">
                <thead>
                    <tr>
                        <th>Semester</th>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Credit</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allResults as $result): ?>
                        <tr>
                            <td><?= htmlspecialchars($result['semesterName']) ?></td>
                            <td><?= htmlspecialchars($result['customCourseId']) ?></td>
                            <td><?= htmlspecialchars($result['courseTitle']) ?></td>
                            <td><?= htmlspecialchars($result['totalCredit']) ?></td>
                            <td><?= htmlspecialchars($result['gradeLetter']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button class="print-button" onclick="printResults()">Print Results</button>
        </div>
    <?php endif; ?>
</div>

<div class="footer">
    &copy; <?= date('Y') ?> Daffodil International University. Developed by Tahsin Hamim.
</div>
</body>
</html>
