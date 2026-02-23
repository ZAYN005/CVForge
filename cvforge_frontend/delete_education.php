<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    if (isset($_POST['delete_all']) && $_POST['delete_all'] === 'true') {
       
        $_SESSION['current_education'] = [];
        
        echo json_encode([
            'status' => 'success',
            'message' => 'All education entries deleted successfully'
        ]);
        exit;
    }
    
   
    $index = $_POST['index'] ?? null;
    
    if ($index !== null && isset($_SESSION['current_education'][$index])) {
       
        array_splice($_SESSION['current_education'], $index, 1);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Education entry deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Education entry not found'
        ]);
    }
    exit;
}


echo json_encode([
    'status' => 'error',
    'message' => 'Invalid request method'
]);
?>