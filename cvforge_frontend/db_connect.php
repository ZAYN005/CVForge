<?php
if (!defined('DB_CONNECTED')) {
    define('DB_CONNECTED', true);

    
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    
    $DB_HOST = '127.0.0.1';
    $DB_USER = 'root';
    $DB_PASS = '1234';
    $DB_NAME = 'cvforge_db';

    try {
        $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        $conn->set_charset('utf8mb4');
        
     
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
    } catch (Exception $e) {
        die("Database connection error: " . $e->getMessage());
    }
}
?>