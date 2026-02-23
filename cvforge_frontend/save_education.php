<?php
header('Content-Type: application/json');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $degree = trim($_POST['degree'] ?? '');
    $school = trim($_POST['school'] ?? '');
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $description = trim($_POST['description'] ?? '');

    
    if (!$user_id || $degree === '' || $school === '' || $description === '') {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }

    
    if (!isset($_SESSION['current_education'])) {
        $_SESSION['current_education'] = [];
    }

  
    $education_exists = false;
    foreach ($_SESSION['current_education'] as $key => $edu) {
        if ($edu['school'] === $school && $edu['degree'] === $degree && $edu['start_date'] === $start_date) {
            
            $_SESSION['current_education'][$key] = [
                'degree' => $degree,
                'school' => $school,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'description' => $description
            ];
            $education_exists = true;
            break;
        }
    }

    // Add new if not exists
    if (!$education_exists) {
        $_SESSION['current_education'][] = [
            'degree' => $degree,
            'school' => $school,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'description' => $description
        ];
    }

    // ✅ 2. THEN: Save to database (optional - for permanent storage)
    // Delete old records for same school+degree+start_date
    $delete_sql = "DELETE FROM education WHERE user_id = ? AND school = ? AND degree = ? AND start_date = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("isss", $user_id, $school, $degree, $start_date);
    
    if (!$delete_stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Delete failed: " . $delete_stmt->error]);
        exit;
    }
    $delete_stmt->close();

    // Insert new one
    $insert_sql = "INSERT INTO education (user_id, degree, school, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("isssss", $user_id, $degree, $school, $start_date, $end_date, $description);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Education saved successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insert failed: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>