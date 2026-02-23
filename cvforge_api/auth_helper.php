<?php
session_start();

function initializeUserSession($user_id, $user_name) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $user_name;
    

    syncExperiencesFromDatabase($user_id);
}

function syncExperiencesFromDatabase($user_id) {
    $servername = "127.0.0.1";
    $username = "root";
    $password = "1234";
    $dbname = "cvforge_db";
    
    
    $_SESSION['current_experience'] = [];
    
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if (!$conn->connect_error) {
            $sql = "SELECT job_title, company, start_date, end_date, description 
                    FROM experience 
                    WHERE user_id = ? 
                    ORDER BY start_date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $_SESSION['current_experience'][] = [
                    'job_title' => $row['job_title'],
                    'company' => $row['company'],
                    'start_date' => $row['start_date'],
                    'end_date' => $row['end_date'],
                    'description' => $row['description']
                ];
            }
            
            $stmt->close();
            $conn->close();
        }
    } catch (Exception $e) {
        error_log("Sync experiences error: " . $e->getMessage());
    }
}
?>