<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = ["logged_in" => false];

if (isset($_SESSION['user_id'])) {
    $response = [
        "logged_in" => true,
        "user_id" => $_SESSION['user_id'],
        "fullname" => $_SESSION['fullname'] ?? '',
        "email" => $_SESSION['email'] ?? ''
    ];
}

echo json_encode($response);
exit;

