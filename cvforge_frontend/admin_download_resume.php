<?php
session_start();


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

include 'db_connect.php';

if (isset($_GET['id'])) {
    $resume_id = intval($_GET['id']);
    
    
    $stmt = $conn->prepare("SELECT * FROM resume_versions WHERE id = ?");
    $stmt->bind_param("i", $resume_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resume = $result->fetch_assoc();
    $stmt->close();
    
    if ($resume) {
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="resume_' . $resume_id . '.pdf"');
        
      
        header('Location: ../download_resume.php?resume_id=' . $resume_id);
        exit;
    } else {
        echo "Resume not found!";
    }
} else {
    echo "No resume ID specified!";
}
?>