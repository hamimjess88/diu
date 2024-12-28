<?php
header('Content-Type: application/json; charset=utf-8');

// Function to fetch data from an API
function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL verification
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:104.0) Gecko/20100101 Firefox/104.0");
    
    $response = curl_exec($ch);
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['error' => "cURL Error: $error"];
    }
    curl_close($ch);
    
    $decodedResponse = json_decode($response, true);
    if ($decodedResponse === null && json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => "Error decoding JSON: " . json_last_error_msg()];
    }
    
    return $decodedResponse;
}

// Validate input parameters for login
if (!isset($_GET['username']) || empty($_GET['username'])) {
    echo json_encode(['error' => 'Please provide the username parameter.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_GET['password']) || empty($_GET['password'])) {
    echo json_encode(['error' => 'Please provide the password parameter.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Input parameters for login
$username = $_GET['username'];
$password = $_GET['password'];

// Simulate login process (replace with actual login logic)
$loginSuccess = true; // Assume login is successful for demonstration

if (!$loginSuccess) {
    echo json_encode(['error' => 'Invalid username or password.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// After successful login, validate input parameters for live results
if (!isset($_GET['semesterId']) || empty($_GET['semesterId'])) {
    echo json_encode(['error' => 'Please provide the semesterId parameter.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_GET['courseSectionId']) || empty($_GET['courseSectionId'])) {
    echo json_encode(['error' => 'Please provide the courseSectionId parameter.'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Input parameters for live results
$semesterId = $_GET['semesterId'];
$courseSectionId = $_GET['courseSectionId'];

// API URLs for live results
$registeredCourseListUrl = "http://software.diu.edu.bd:8006/liveResult/registeredCourseList?semesterId=$semesterId";
$liveResultUrl = "http://software.diu.edu.bd:8006/liveResult?courseSectionId=$courseSectionId";

// Fetch data from both APIs
$registeredCourseList = fetchData($registeredCourseListUrl);
$liveResult = fetchData($liveResultUrl);

// Combine the responses
$response = [
    'registeredCourseList' => $registeredCourseList,
    'liveResult' => $liveResult
];

// Output the combined response
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>