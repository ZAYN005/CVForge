<?php
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


if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];


$headline = $_POST['headline'] ?? '';
$about = $_POST['about'] ?? '';
$objective = $_POST['objective'] ?? '';
$years_experience = $_POST['years_experience'] ?? 0;
$strengths = $_POST['strengths'] ?? '';
$soft_skills = $_POST['soft_skills'] ?? '';
$hobbies = $_POST['hobbies'] ?? '';
$achievements = $_POST['achievements'] ?? '';
$projects = $_POST['projects'] ?? '';
$linkedin = $_POST['linkedin'] ?? '';
$portfolio = $_POST['portfolio'] ?? '';


$_SESSION['current_summary'] = [
    [
        'headline' => $headline,
        'about' => $about,
        'objective' => $objective,
        'years_experience' => $years_experience,
        'strengths' => $strengths,
        'soft_skills' => $soft_skills,
        'hobbies' => $hobbies,
        'achievements' => $achievements,
        'projects' => $projects,
        'linkedin' => $linkedin,
        'portfolio' => $portfolio
    ]
];

try {

    $check_stmt = $conn->prepare("SELECT 1 FROM summary WHERE user_id = ?");
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
       
        $stmt = $conn->prepare("UPDATE summary SET headline=?, about=?, objective=?, years_experience=?, strengths=?, soft_skills=?, hobbies=?, achievements=?, projects=?, linkedin=?, portfolio=? WHERE user_id=?");
        $stmt->bind_param("sssisssssssi", $headline, $about, $objective, $years_experience, $strengths, $soft_skills, $hobbies, $achievements, $projects, $linkedin, $portfolio, $user_id);
    } else {
       
        $stmt = $conn->prepare("INSERT INTO summary (user_id, headline, about, objective, years_experience, strengths, soft_skills, hobbies, achievements, projects, linkedin, portfolio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssisssssss", $user_id, $headline, $about, $objective, $years_experience, $strengths, $soft_skills, $hobbies, $achievements, $projects, $linkedin, $portfolio);
    }
    
    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success", 
            "message" => "Summary saved successfully!"
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Failed to save summary: " . $conn->error
        ]);
    }
    
    $stmt->close();
    $check_stmt->close();
    
} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "message" => "Database error: " . $e->getMessage()
    ]);
}

$conn->close();
?>