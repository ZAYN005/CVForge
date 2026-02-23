<?php
session_start();
include '../cvforge_api/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resume_id'])) {
    $resume_id = $_POST['resume_id'];
    $user_id = $_SESSION['user_id'] ?? null;
    
    if ($user_id && $conn) {
        
        $stmt = $conn->prepare("SELECT * FROM resume_versions WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $resume_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $original = $result->fetch_assoc();
        
        if ($original) {
        
            $new_version_name = "Copy of " . $original['version_name'];
            
            
            $insert_stmt = $conn->prepare("INSERT INTO resume_versions 
                (user_id, version_name, template_used, header_data, experience_data, education_data, skills_data, languages_data, summary_data) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $insert_stmt->bind_param("issssssss", 
                $user_id, 
                $new_version_name, 
                $original['template_used'],
                $original['header_data'], 
                $original['experience_data'],
                $original['education_data'], 
                $original['skills_data'],
                $original['languages_data'], 
                $original['summary_data']
            );
            
            if ($insert_stmt->execute()) {
                $_SESSION['success_message'] = "Resume duplicated successfully!";
            } else {
                $_SESSION['error_message'] = "Error duplicating resume";
            }
            
            $insert_stmt->close();
        }
        $stmt->close();
    }
}

header('Location: dashboard.php');
exit;
?>