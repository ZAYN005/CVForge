<?php
session_start();
include '../cvforge_api/db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resume_id'])) {
    $resume_id = $_POST['resume_id'];
    $user_id = $_SESSION['user_id'] ?? null;
    
    if ($user_id && $conn) {
        try {
            $stmt = $conn->prepare("DELETE FROM resume_versions WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $resume_id, $user_id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Resume deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete resume']);
            }
            $stmt->close();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request or user not logged in']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid method or missing resume ID']);
}
?>