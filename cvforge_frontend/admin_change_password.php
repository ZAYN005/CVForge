<?php
session_start();
include "db_connect.php";

// Check login
if (!isset($_SESSION['admin_logged_in']) || !isset($_SESSION['admin_id'])) {
    die("Admin not logged in!");
}

$admin_id = $_SESSION['admin_id'];

// Get form data
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate input
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    die("All fields are required!");
}

if ($new_password !== $confirm_password) {
    die("New passwords do not match!");
}

// Fetch admin record
$query = $conn->prepare("SELECT password FROM admin_users WHERE id = ?");
$query->bind_param("i", $admin_id);
$query->execute();
$result = $query->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    die("Admin not found!");
}

// Check current password
if (!password_verify($current_password, $admin['password'])) {
    die("Current password is incorrect!");
}

// Update password
$new_hashed = password_hash($new_password, PASSWORD_DEFAULT);

$update = $conn->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
$update->bind_param("si", $new_hashed, $admin_id);

if ($update->execute()) {
    echo "Password updated successfully!";
} else {
    echo "Error updating password!";
}

?>
