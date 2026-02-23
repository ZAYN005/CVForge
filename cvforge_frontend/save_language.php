<?php
header('Content-Type: application/json');
session_start();

$conn = new mysqli("localhost", "root", "1234", "cvforge_db");
if ($conn->connect_error) {
  echo json_encode(["status" => "error", "message" => "DB connection failed"]);
  exit;
}

$user_id = $_SESSION['user_id'] ?? 1;
$languages = $_POST['language'] ?? [];
$proficiency = $_POST['proficiency'] ?? [];


$_SESSION['current_languages'] = [];

for ($i = 0; $i < count($languages); $i++) {
    $lang = trim($languages[$i]);
    $prof = intval($proficiency[$i]);
    
    if (!empty($lang)) {
        $_SESSION['current_languages'][] = [
            'language' => $lang,
            'proficiency' => $prof
        ];
    }
}

$conn->query("DELETE FROM languages WHERE user_id = '$user_id'");

$stmt = $conn->prepare("INSERT INTO languages (user_id, language, proficiency) VALUES (?, ?, ?)");
if (!$stmt) {
  echo json_encode(["status" => "error", "message" => "Prepare failed"]);
  exit;
}

for ($i = 0; $i < count($languages); $i++) {
  $lang = $languages[$i];
  $prof = intval($proficiency[$i]);
  
  if (!empty($lang)) {
    $stmt->bind_param("isi", $user_id, $lang, $prof);
    $stmt->execute();
  }
}

$stmt->close();
$conn->close();
echo json_encode(["status" => "success", "message" => "Languages saved successfully!"]);
?>