<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    

    mysqli_begin_transaction($conn);
    
    try {
       
        $tables = [
            'skills' => 'user_id',
            'experience' => 'user_id', 
            'education' => 'user_id',
            'languages' => 'user_id',
            'summary' => 'user_id',
            'header' => 'user_id',
            'resume_versions' => 'user_id'
        ];
        
        foreach ($tables as $table => $column) {
            $delete_query = "DELETE FROM $table WHERE $column = ?";
            $stmt = mysqli_prepare($conn, $delete_query);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        
    
        $delete_user = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $delete_user);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        if ($result) {
            mysqli_commit($conn);
            $_SESSION['success_message'] = "User deleted successfully!";
        } else {
            throw new Exception("Error deleting user");
        }
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error_message'] = "Error deleting user: " . mysqli_error($conn);
    }
    
    header('Location: admin_dashboard.php');
    exit;
} else {
    $_SESSION['error_message'] = "Invalid request method";
    header('Location: admin_dashboard.php');
    exit;
}
?>