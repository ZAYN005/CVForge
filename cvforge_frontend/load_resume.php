<?php
session_start();
include '../cvforge_api/db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $resume_id = $input['resume_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;
    
    if ($resume_id && $user_id && $conn) {
        $stmt = $conn->prepare("SELECT * FROM resume_versions WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $resume_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $resume = $result->fetch_assoc();
        
        if ($resume) {
        
            unset($_SESSION['current_header']);
            unset($_SESSION['current_experiences']);
            unset($_SESSION['current_education']);
            unset($_SESSION['current_skills']);
            unset($_SESSION['current_languages']);
            unset($_SESSION['current_summary']);
            
            
            $_SESSION['current_header'] = json_decode($resume['header_data'], true) ?: [];
            $_SESSION['current_experiences'] = json_decode($resume['experience_data'], true) ?: [];
            $_SESSION['current_education'] = json_decode($resume['education_data'], true) ?: [];
            $_SESSION['current_skills'] = json_decode($resume['skills_data'], true) ?: [];
            $_SESSION['current_languages'] = json_decode($resume['languages_data'], true) ?: [];
            $_SESSION['current_summary'] = json_decode($resume['summary_data'], true) ?: [];
            
            $_SESSION['editing_resume_id'] = $resume_id;
            
            echo json_encode(['success' => true, 'message' => 'Resume loaded successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Resume not found']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
}
?>