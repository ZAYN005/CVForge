<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

$user_id = $_SESSION['user_id'];


$job_title   = $_POST['job_title'] ?? '';
$company     = $_POST['company'] ?? '';
$start_date  = $_POST['start_date'] ?? '';
$end_date    = $_POST['end_date'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($job_title) || empty($company) || empty($description)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}


if (!isset($_SESSION['current_experience'])) {
    $_SESSION['current_experience'] = [];
}


$_SESSION['current_experience'][] = [
    'job_title' => $job_title,
    'company' => $company,
    'start_date' => $start_date,
    'end_date' => $end_date,
    'description' => $description
];


error_log("After save - Session experiences: " . print_r($_SESSION['current_experience'], true));


$servername = "127.0.0.1";
$username = "root";
$password = "1234";
$dbname = "cvforge_db";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if (!$conn->connect_error) {
   
        $insert_sql = "INSERT INTO experience (user_id, job_title, company, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("isssss", $user_id, $job_title, $company, $start_date, $end_date, $description);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
} catch (Exception $e) {
   
    error_log("Database error: " . $e->getMessage());
}

echo json_encode(["status" => "success", "message" => "Experience saved successfully!"]);
exit();
?>
