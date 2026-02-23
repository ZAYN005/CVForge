<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

session_start();


$servername = "127.0.0.1";
$username = "root";
$password = "1234";
$dbname = "cvforge_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$user_id = $_SESSION['user_id'] ?? 1;

$skill_name = $_POST['skill_name'] ?? '';
$proficiency = $_POST['proficiency'] ?? '';
$years_experience = $_POST['years_experience'] ?? '';
$rating = $_POST['rating'] ?? '';

if (empty($skill_name) || empty($proficiency)) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}


if (!isset($_SESSION['current_skills'])) {
    $_SESSION['current_skills'] = [];
}


$skill_exists = false;
foreach ($_SESSION['current_skills'] as $key => $skill) {
    if ($skill['skill_name'] === $skill_name && $skill['proficiency'] === $proficiency) {
       
        $_SESSION['current_skills'][$key] = [
            'skill_name' => $skill_name,
            'proficiency' => $proficiency,
            'years_experience' => $years_experience,
            'rating' => $rating
        ];
        $skill_exists = true;
        break;
    }
}


if (!$skill_exists) {
    $_SESSION['current_skills'][] = [
        'skill_name' => $skill_name,
        'proficiency' => $proficiency,
        'years_experience' => $years_experience,
        'rating' => $rating
    ];
}


$check_sql = "SELECT 1 FROM skills WHERE user_id = ? AND skill_name = ? AND proficiency = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("iss", $user_id, $skill_name, $proficiency);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    
    $sql = "UPDATE skills SET years_experience = ?, rating = ? WHERE user_id = ? AND skill_name = ? AND proficiency = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissi", $years_experience, $rating, $user_id, $skill_name, $proficiency);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Skill updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    
    $sql = "INSERT INTO skills (user_id, skill_name, proficiency, years_experience, rating) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issii", $user_id, $skill_name, $proficiency, $years_experience, $rating);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Skill added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insert failed: " . $stmt->error]);
    }
    $stmt->close();
}

$check_stmt->close();
$conn->close();
?>