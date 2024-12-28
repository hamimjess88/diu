<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['accessToken']) && isset($data['username'])) {
    $_SESSION['accessToken'] = $data['accessToken'];
    $_SESSION['username'] = $data['username'];
    http_response_code(200);
    echo json_encode(["message" => "Session set successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Failed to set session"]);
}
?>
