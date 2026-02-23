<?php
session_set_cookie_params([
  'path' => '/', 
  'httponly' => true,
  'samesite' => 'Lax'
]);

session_start();
include 'db_connect.php';

$_SESSION = [];
session_regenerate_id(true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo "<script>alert('Please enter both email and password.');
        window.location.href='http://localhost/cvforge_frontend/login.html';</script>";
        exit;
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        echo "<script>alert('No user found with that email.');
        window.location.href='http://localhost/cvforge_frontend/login.html';</script>";
        exit;
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        echo "<script>alert('Incorrect password.');
        window.location.href='http://localhost/cvforge_frontend/login.html';</script>";
        exit;
    }

   
    $_SESSION = [];
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['fullname'];
    $_SESSION['email'] = $user['email'];

    foreach ([
        'current_header','current_experience','current_education',
        'current_skills','current_language','current_summary',
        'builder_initialized','current_user'
    ] as $key) unset($_SESSION[$key]);


    $redirect = $_POST['redirect'] ?? "";
    $template_id = $_POST['template_id'] ?? "";

    $redirect_to = "http://localhost/cvforge_frontend/home.php";

    if (!empty($redirect)) {

        if (!empty($template_id)) {

            $_SESSION['selected_template_id'] = intval($template_id);

            $redirect_to = "http://localhost/cvforge_frontend/builder.php?template={$template_id}";

        } else {

            $redirect_to = "http://localhost/cvforge_frontend/{$redirect}";

        }
    }

    echo "<script>alert('Login successful!'); window.location.href='$redirect_to';</script>";
    exit;
}
?>
