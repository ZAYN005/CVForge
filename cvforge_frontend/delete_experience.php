<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
  
    if (isset($_POST['delete_all']) && $_POST['delete_all'] === 'true') {
       
        $_SESSION['current_experience'] = [];
        
       
        $user_id = $_SESSION['user_id'] ?? 1;
        $servername = "127.0.0.1";
        $username = "root";
        $password = "1234";
        $dbname = "cvforge_db";
        
        try {
            $conn = new mysqli($servername, $username, $password, $dbname);
            if (!$conn->connect_error) {
                $delete_sql = "DELETE FROM experience WHERE user_id = ?";
                $stmt = $conn->prepare($delete_sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $stmt->close();
                $conn->close();
            }
        } catch (Exception $e) {
            error_log("Database delete all error: " . $e->getMessage());
        }
        
        echo json_encode(['status' => 'success', 'message' => 'All experiences deleted successfully!']);
        exit;
    }
    
    
    if (isset($_POST['index'])) {
        $index = (int)$_POST['index'];
        
        if (isset($_SESSION['current_experience'][$index])) {
          
            $experience_to_delete = $_SESSION['current_experience'][$index];
            
           
            array_splice($_SESSION['current_experience'], $index, 1);
            
           
            $user_id = $_SESSION['user_id'] ?? 1;
            $servername = "127.0.0.1";
            $username = "root";
            $password = "1234";
            $dbname = "cvforge_db";
            
            try {
                $conn = new mysqli($servername, $username, $password, $dbname);
                if (!$conn->connect_error) {
                    $delete_sql = "DELETE FROM experience WHERE user_id = ? AND company = ? AND job_title = ? AND start_date = ?";
                    $stmt = $conn->prepare($delete_sql);
                    $stmt->bind_param("isss", $user_id, $experience_to_delete['company'], $experience_to_delete['job_title'], $experience_to_delete['start_date']);
                    $stmt->execute();
                    $stmt->close();
                    $conn->close();
                }
            } catch (Exception $e) {
                error_log("Database delete error: " . $e->getMessage());
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Experience deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Experience not found.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No index provided.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>