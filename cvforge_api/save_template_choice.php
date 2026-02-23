<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_start();


$raw_input = file_get_contents('php://input');
$input = json_decode($raw_input, true);
$template_id = $input['template_id'] ?? null;

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Please log in first"]);
    exit;
}

if (!$template_id) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing template_id"]);
    exit;
}


$template_id = intval($template_id);
if ($template_id < 1 || $template_id > 6) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid template_id"]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
 
    include __DIR__ . '/db_connect.php';
    
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    
    $_SESSION['selected_template_id'] = $template_id;
    
    
    try {
        $check_sql = "SELECT id FROM user_template_choices WHERE user_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $update_sql = "UPDATE user_template_choices SET template_id = ?, updated_at = NOW() WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ii", $template_id, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            $insert_sql = "INSERT INTO user_template_choices (user_id, template_id) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ii", $user_id, $template_id);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
        
        $check_stmt->close();
    } catch (Exception $db_error) {
        
        error_log("Database save failed (but continuing): " . $db_error->getMessage());
    }
    
  
    echo json_encode([
        "status" => "success", 
        "message" => "Template selected successfully!",
        "selected_template" => $template_id,
        "user_id" => $user_id,
        "redirect_url" => "builder.php"
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error", 
        "message" => "Server error: " . $e->getMessage()
    ]);
}

exit;
?>