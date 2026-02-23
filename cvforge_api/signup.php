<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include 'db_connect.php';

function sendResponse($type, $message) {
    echo $type . ':' . $message;
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
   
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        sendResponse('error', 'All fields are required!');
    }
    
    if ($password !== $confirm_password) {
        sendResponse('error', 'Passwords do not match!');
    }
    
    if (strlen($password) < 8) {
        sendResponse('error', 'Password must be at least 8 characters long');
    }
    
    
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    
    if ($check_stmt) {
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            sendResponse('error', 'Email already exists!');
        }
        $check_stmt->close();
    }
    

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    

    $insert_sql = "INSERT INTO users (fullname, email, password, has_completed_RESUME) VALUES (?, ?, ?, 0)";
    $insert_stmt = $conn->prepare($insert_sql);
    
    if ($insert_stmt) {
        $insert_stmt->bind_param("sss", $fullname, $email, $hashed_password);
        
        if ($insert_stmt->execute()) {
            sendResponse('success', 'Registration successful! Redirecting to login...');
        } else {
            sendResponse('error', 'Database insert failed: ' . $insert_stmt->error);
        }
        $insert_stmt->close();
    } else {
        sendResponse('error', 'Failed to prepare SQL statement');
    }
    
    $conn->close();
    
} else {
    sendResponse('error', 'Invalid request method');
}
?>