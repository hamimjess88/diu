<?php

header('Content-Type: application/json; charset=utf-8');

// Validate input parameters
if (!isset($_GET['studentId']) || empty($_GET['studentId'])) {
    $response = [
        'error' => 'Please provide the studentId parameter.'
    ];
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_GET['semesterId']) || empty($_GET['semesterId'])) {
    $response = [
        'error' => 'Please provide the semesterId parameter.'
    ];
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Input parameters
$studentId = $_GET['studentId'];
$semesterId = $_GET['semesterId'];

// API URLs
$studentInfoUrl = "http://software.diu.edu.bd:8006/result/studentInfo?studentId=$studentId";
$semesterResultUrl = "http://software.diu.edu.bd:8006/result?grecaptcha=&semesterId=$semesterId&studentId=$studentId";

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

// Fetch data from both APIs
$studentInfo = fetchData($studentInfoUrl);
$semesterResult = fetchData($semesterResultUrl);

// Combine the responses
$response = [
    'studentInfo' => $studentInfo,
    'semesterResult' => $semesterResult
];

// Output the combined response
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>

