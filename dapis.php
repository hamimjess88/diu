<?php

header('Content-Type: application/json; charset=utf-8');

// Validate input parameters
if (!isset($_GET['username']) || empty($_GET['username'])) {
    $response = [
        'error' => 'Please provide the username parameter.'
    ];
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_GET['password']) || empty($_GET['password'])) {
    $response = [
        'error' => 'Please provide the password parameter.'
    ];
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_GET['grecaptcha'])) {
    $response = [
        'error' => 'Please provide the grecaptcha parameter.'
    ];
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Input parameters
$username = $_GET['username'];
$password = $_GET['password'];
$grecaptcha = $_GET['grecaptcha'];

// API URLs
$loginUrl = "http://software.diu.edu.bd:8006/login";
$userDetailsUrl = "http://software.diu.edu.bd:8006/userDetails?username=$username";

// Function to fetch data from an API
function fetchData($url, $postData = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL verification
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:104.0) Gecko/20100101 Firefox/104.0");

    if ($postData !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
    }

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

// Fetch data from login API
$loginPayload = [
    "username" => $username,
    "password" => $password,
    "grecaptcha" => $grecaptcha
];

$loginResponse = fetchData($loginUrl, $loginPayload);

// If login is successful, fetch user details
if (isset($loginResponse['message']) && $loginResponse['message'] === 'success') {
    $userDetails = fetchData($userDetailsUrl);
} else {
    $userDetails = ['error' => 'Unable to fetch user details. Login failed.'];
}

// Combine the responses
$response = [
    'loginResponse' => $loginResponse,
    'userDetails' => $userDetails
];

// Output the combined response
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>
