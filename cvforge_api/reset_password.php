<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

include 'db_connect.php';

$token = $_POST['token'] ?? '';
$new_password = $_POST['password'] ?? '';

if (!$token || !$new_password) {
  echo json_encode(["status" => "error", "message" => "Missing fields"]);
  exit;
}


$stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token=?");
$stmt->bind_param("s", $token);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  echo json_encode(["status" => "error", "message" => "Invalid or expired token"]);
  exit;
}

$row = $res->fetch_assoc();
if (strtotime($row['expires_at']) < time()) {
  echo json_encode(["status" => "error", "message" => "Token expired"]);
  exit;
}

$email = $row['email'];


$hashed = password_hash($new_password, PASSWORD_BCRYPT);
$update = $conn->prepare("UPDATE users SET password=? WHERE email=?");
$update->bind_param("ss", $hashed, $email);
$update->execute();


$conn->query("DELETE FROM password_resets WHERE email='$email'");

echo json_encode(["status" => "success", "message" => "Password updated successfully!"]);
?>
