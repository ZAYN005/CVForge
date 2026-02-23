<?php
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

include 'db_connect.php';

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
  echo json_encode(["status" => "error", "message" => "Email is required"]);
  exit;
}
$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo json_encode(["status" => "error", "message" => "No account found with that email"]);
  exit;
}

$token = bin2hex(random_bytes(16));
$expires = date("Y-m-d H:i:s", time() + 1800);

$conn->query("DELETE FROM password_resets WHERE email='$email'");
$stmt2 = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
$stmt2->bind_param("sss", $email, $token, $expires);
$stmt2->execute();

$link = "http://localhost/cvforge_frontend/reset_password.php?token=$token";

echo json_encode([
  "status" => "success",
  "message" => "Password reset link generated successfully.",
  "reset_link" => $link
]);
?>
