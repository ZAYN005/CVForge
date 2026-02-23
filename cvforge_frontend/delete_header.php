<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    if (isset($_POST['delete_all']) && $_POST['delete_all'] === 'true') {
        
        $_SESSION['current_header'] = [];
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Header information deleted successfully'
        ]);
        exit;
    }
    
   
    $index = $_POST['index'] ?? null;
    
    if ($index !== null && isset($_SESSION['current_header'][$index])) {
        
        array_splice($_SESSION['current_header'], $index, 1);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Header information deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Header information not found'
        ]);
    }
    exit;
}


echo json_encode([
    'status' => 'error',
    'message' => 'Invalid request method'
]);
?>