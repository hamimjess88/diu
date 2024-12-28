<?php

header('Content-Type: application/json; charset=utf-8');

// External API URL
$semesterListUrl = "http://software.diu.edu.bd:8006/result/semesterList";

// Function to fetch data from an API
function fetchSemesterList($url) {
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

// Fetch the semester list
$response = fetchSemesterList($semesterListUrl);

// Output the response
if (isset($response['error'])) {
    echo json_encode(['error' => $response['error']], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
