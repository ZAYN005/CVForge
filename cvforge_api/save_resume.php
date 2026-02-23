<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['user_id'])) {
  echo "⚠️ Session expired. Please log in again.";
  exit;
}

$user_id = $_SESSION['user_id'];

$first = $_POST['first_name'] ?? '';
$last  = $_POST['last_name'] ?? '';
$city  = $_POST['city'] ?? '';
$state = $_POST['state'] ?? '';
$zip   = $_POST['zip'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

$stmt = $conn->prepare("INSERT INTO resumes 
(user_id, first_name, last_name, city, state, zip, email, phone) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssss", $user_id, $first, $last, $city, $state, $zip, $email, $phone);

echo $stmt->execute() ? "✅ Resume saved!" : "❌ " . $conn->error;

$stmt->close();
$conn->close();
?>
