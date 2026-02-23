<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


$servername = "localhost";
$username   = "root";
$password   = "1234";
$dbname     = "cvforge_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed'
    ]);
    exit;
}


if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; 
}
$user_id = $_SESSION['user_id'];


$first  = $_POST['first_name'] ?? '';
$last   = $_POST['last_name'] ?? '';
$city   = $_POST['city'] ?? '';
$state  = $_POST['state'] ?? '';
$zip    = $_POST['zip'] ?? '';
$email  = $_POST['email'] ?? '';
$phone  = $_POST['phone'] ?? '';


$profile_image = null;
if (!empty($_FILES['profile_image']['name'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $file_name = time() . '_' . basename($_FILES['profile_image']['name']);
    $target_file = $target_dir . $file_name;
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
        $profile_image = $file_name;
    }
}


$_SESSION['current_header'] = [
    [
        'first_name' => $first,
        'last_name' => $last,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'email' => $email,
        'phone' => $phone,
        'profile_image' => $profile_image
    ]
];


$_SESSION['user_name'] = $first . ' ' . $last;


$check = $conn->prepare("SELECT id FROM header WHERE user_id = ?");
$check->bind_param("i", $user_id);
$check->execute();
$result = $check->get_result();

$success = false;

if ($result->num_rows > 0) {

    $sql = "UPDATE header 
            SET first_name = ?, last_name = ?, city = ?, state = ?, zip = ?, email = ?, phone = ?, profile_image = ?
            WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $first, $last, $city, $state, $zip, $email, $phone, $profile_image, $user_id);
    $success = $stmt->execute();
    $stmt->close();
} else {
    
    $sql = "INSERT INTO header (user_id, first_name, last_name, city, state, zip, email, phone, profile_image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssss", $user_id, $first, $last, $city, $state, $zip, $email, $phone, $profile_image);
    $success = $stmt->execute();
    $stmt->close();
}

$check->close();
$conn->close();


header('Content-Type: application/json');
if ($success) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Header saved successfully!'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to save header'
    ]);
}
exit();
?>