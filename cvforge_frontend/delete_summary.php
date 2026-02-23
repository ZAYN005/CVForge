<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    if (isset($_POST['delete_all']) && $_POST['delete_all'] === 'true') {
        
        $_SESSION['current_summary'] = [];
        
        echo json_encode([
            'status' => 'success',
            'message' => 'All summary entries deleted successfully'
        ]);
        exit;
    }
    
    
    $index = $_POST['index'] ?? null;
    
    if ($index !== null && isset($_SESSION['current_summary'][$index])) {
       
        array_splice($_SESSION['current_summary'], $index, 1);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Summary entry deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Summary entry not found'
        ]);
    }
    exit;
}


echo json_encode([
    'status' => 'error',
    'message' => 'Invalid request method'
]);
?>