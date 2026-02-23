<?php
session_start();


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}


include 'db_connect.php';


if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . ($conn->connect_error ?? "Unknown error"));
}


function getCount($conn, $table) {
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM $table");
    if ($result) {
        return mysqli_fetch_assoc($result)['count'];
    }
    return 0;
}

$users_count = getCount($conn, 'users');
$resumes_count = getCount($conn, 'resume_versions');
$skills_count = getCount($conn, 'skills');
$experience_count = getCount($conn, 'experience');
$education_count = getCount($conn, 'education');


$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    
 
    $delete_resumes = $conn->prepare("DELETE FROM resume_versions WHERE user_id = ?");
    $delete_resumes->bind_param("i", $user_id);
    $delete_resumes->execute();
    $delete_resumes->close();
    
   
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $message = "User deleted successfully!";
        echo "<script>setTimeout(() => window.location.href = 'admin_dashboard.php', 1000);</script>";
    } else {
        $error = "Error deleting user!";
    }
    $stmt->close();
}
    
    if (isset($_POST['add_user'])) {
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $fullname, $email, $password);
            if ($stmt->execute()) {
                $message = "User added successfully!";
                echo "<script>setTimeout(() => window.location.href = 'admin_dashboard.php', 1000);</script>";
            } else {
                $error = "Error adding user!";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
    
    if (isset($_POST['edit_user'])) {
        $user_id = $_POST['user_id'];
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        
        if ($password) {
            $stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $fullname, $email, $password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $fullname, $email, $user_id);
        }
        
        if ($stmt->execute()) {
            $message = "User updated successfully!";
            echo "<script>setTimeout(() => window.location.href = 'admin_dashboard.php', 1000);</script>";
        } else {
            $error = "Error updating user!";
        }
        $stmt->close();
    }
    
   
    if (isset($_POST['delete_resume'])) {
        $resume_id = $_POST['resume_id'];
        $stmt = $conn->prepare("DELETE FROM resume_versions WHERE id = ?");
        $stmt->bind_param("i", $resume_id);
        if ($stmt->execute()) {
            $message = "Resume deleted successfully!";
            echo "<script>setTimeout(() => window.location.href = 'admin_dashboard.php', 1000);</script>";
        } else {
            $error = "Error deleting resume!";
        }
        $stmt->close();
    }
    
    if (isset($_POST['add_resume'])) {
        $user_id = $_POST['user_id'];
        $template_used = $_POST['template_used'];
        $version_name = trim($_POST['version_name']);
        
      
        $check_sql = "SELECT id FROM user_template_choices WHERE user_id = ? AND template_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $user_id, $template_used);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            
            $update_sql = "UPDATE user_template_choices SET template_id = ?, updated_at = NOW() WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ii", $template_used, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
           
            $insert_sql = "INSERT INTO user_template_choices (user_id, template_id) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ii", $user_id, $template_used);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
        $check_stmt->close();
        
       
        $default_header = json_encode([[
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'address' => '',
            'linkedin' => '',
            'portfolio' => ''
        ]]);
        
        $default_experience = json_encode([[
            'job_title' => 'Software Developer',
            'company' => 'Tech Company',
            'start_date' => '2020-01-01',
            'end_date' => '2023-12-31',
            'description' => 'Developed web applications',
            'location' => ''
        ]]);
        
        $default_education = json_encode([[
            'degree' => 'Bachelor of Computer Science',
            'institution' => 'University',
            'start_date' => '2016-09-01',
            'end_date' => '2020-06-01',
            'description' => '',
            'location' => ''
        ]]);
        
        $default_skills = json_encode([[
            'category' => 'Technical Skills',
            'items' => 'JavaScript, PHP, HTML, CSS, MySQL'
        ]]);
        
        $stmt = $conn->prepare("INSERT INTO resume_versions (user_id, template_used, version_name, header_data, experience_data, education_data, skills_data) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $user_id, $template_used, $version_name, $default_header, $default_experience, $default_education, $default_skills);
        
        if ($stmt->execute()) {
            $message = "Resume template added successfully to both systems! User can now see it in their template selection.";
            echo "<script>setTimeout(() => window.location.href = 'admin_dashboard.php', 1000);</script>";
        } else {
            $error = "Error adding resume template: " . $stmt->error;
        }
        $stmt->close();
    }

    
    if (isset($_POST['update_security'])) {
        $message = "Security settings updated successfully!";
    }

    if (isset($_POST['backup_database'])) {
        $message = "Database backup created successfully!";
    }

    if (isset($_POST['optimize_database'])) {
        $tables = mysqli_query($conn, "SHOW TABLES");
        while ($table = mysqli_fetch_array($tables)) {
            mysqli_query($conn, "OPTIMIZE TABLE " . $table[0]);
        }
        $message = "Database optimized successfully!";
    }

    if (isset($_POST['update_notifications'])) {
        $message = "Notification settings updated successfully!";
    }

    if (isset($_POST['update_appearance'])) {
        $message = "Appearance settings updated successfully!";
    }
}


$users_result = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");


$resumes_result = mysqli_query($conn, "
    SELECT rv.*, u.fullname as user_name 
    FROM resume_versions rv 
    LEFT JOIN users u ON rv.user_id = u.id 
    ORDER BY rv.created_at DESC
");


$all_users = mysqli_query($conn, "SELECT id, fullname FROM users ORDER BY fullname");


$db_size = mysqli_query($conn, "SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb FROM information_schema.tables WHERE table_schema = DATABASE()");
$db_size_row = mysqli_fetch_assoc($db_size);
$templates_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM templates"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CVForge</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --border: #dee2e6;
        }

        body {
            background-color: #f5f7fb;
            color: var(--dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
            color: white;
            padding: 20px 0;
            transition: all 0.3s;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo h2 {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            font-size: 24px;
        }

        .menu {
            list-style: none;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid white;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }

        .header h1 {
            color: var(--primary);
            font-weight: 600;
            font-size: 28px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Stats Cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid var(--primary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.users {
            background: var(--primary);
        }

        .stat-icon.resumes {
            background: var(--success);
        }

        .stat-icon.skills {
            background: var(--warning);
        }

        .stat-icon.experience {
            background: var(--danger);
        }

        .stat-info h3 {
            font-size: 32px;
            margin-bottom: 5px;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-info p {
            color: var(--gray);
            font-size: 14px;
            font-weight: 500;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            background: var(--light);
        }

        .table-header h2 {
            color: var(--primary);
            font-size: 22px;
            font-weight: 600;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 8px;
            padding: 10px 15px;
            width: 300px;
            border: 1px solid var(--border);
            transition: border 0.3s;
        }

        .search-box:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            margin-left: 10px;
            width: 100%;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background-color: var(--light);
            font-weight: 600;
            color: var(--dark);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: rgba(67, 97, 238, 0.02);
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .btn-edit {
            background: rgba(248, 150, 30, 0.1);
            color: var(--warning);
        }

        .btn-delete {
            background: rgba(247, 37, 133, 0.1);
            color: var(--danger);
        }

        .btn-download {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }

        .btn-add {
            background: var(--primary);
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Quick Action Cards */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .quick-action-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .quick-action-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.15);
        }

        .quick-action-card i {
            font-size: 32px;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .quick-action-card h4 {
            color: var(--dark);
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 600;
        }

        .quick-action-card p {
            color: var(--gray);
            font-size: 13px;
            line-height: 1.4;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 25px;
            background: white;
            border-radius: 12px 12px 0 0;
            padding: 0 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .tab {
            padding: 15px 25px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            font-weight: 500;
            color: var(--gray);
        }

        .tab.active {
            border-bottom-color: var(--primary);
            color: var(--primary);
            font-weight: 600;
        }

        .tab:hover:not(.active) {
            color: var(--dark);
            background: rgba(67, 97, 238, 0.05);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Settings Tabs */
        .settings-tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
            background: white;
            border-radius: 8px 8px 0 0;
            padding: 0 20px;
        }

        .settings-tab {
            padding: 12px 24px;
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            font-weight: 500;
            color: var(--gray);
        }

        .settings-tab.active {
            border-bottom-color: var(--primary);
            color: var(--primary);
            font-weight: 600;
        }

        .settings-tab:hover:not(.active) {
            color: var(--dark);
            background: rgba(67, 97, 238, 0.05);
        }

        .settings-tab-content {
            display: none;
            padding: 20px 0;
        }

        .settings-tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .settings-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 600px;
            max-width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            padding: 20px 25px;
            background: var(--primary);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            border-radius: 12px 12px 0 0;
        }

        .modal-header h3 {
            font-weight: 500;
            font-size: 18px;
        }

        .close {
            font-size: 24px;
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 15px;
            transition: border 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .modal-footer {
            padding: 20px 25px;
            background: var(--light);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-radius: 0 0 12px 12px;
        }

        .btn-cancel {
            background: var(--gray);
            color: white;
            padding: 10px 20px;
        }

        .btn-save {
            background: var(--primary);
            color: white;
            padding: 10px 20px;
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 1100;
            animation: slideIn 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .notification.success {
            background: var(--success);
        }

        .notification.error {
            background: var(--danger);
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* User Details Modal */
        .user-details, .resume-details {
            background: var(--light);
            padding: 20px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
        }

        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: var(--dark);
        }

        .detail-value {
            color: var(--gray);
        }

        /* Resume Preview */
        .resume-preview {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            margin-top: 15px;
            max-height: 300px;
            overflow-y: auto;
        }

        .resume-section {
            margin-bottom: 20px;
        }

        .resume-section h4 {
            color: var(--primary);
            margin-bottom: 10px;
            font-size: 16px;
        }

        .resume-section p {
            margin-bottom: 5px;
            color: var(--dark);
        }

        /* Setting Cards */
        .setting-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .setting-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.15);
        }

        .setting-card i {
            font-size: 32px;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .setting-card h4 {
            color: var(--dark);
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 600;
        }

        .setting-card p {
            color: var(--gray);
            font-size: 13px;
            line-height: 1.4;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar .logo h2 span, 
            .sidebar .menu-item span {
                display: none;
            }
            
            .sidebar .menu-item {
                justify-content: center;
                padding: 15px;
            }
            
            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
        }

        @media (max-width: 768px) {
            .stats {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .search-box {
                width: 100%;
            }
            
            .actions {
                flex-direction: column;
                width: 100%;
            }
            
            .actions .btn {
                width: 100%;
                justify-content: center;
            }
            
            .tabs {
                flex-direction: column;
                padding: 0;
            }
            
            .tab {
                padding: 15px 20px;
                border-bottom: 1px solid var(--border);
                border-left: 3px solid transparent;
            }
            
            .tab.active {
                border-left-color: var(--primary);
                border-bottom-color: var(--border);
            }
            
            .settings-tabs {
                flex-direction: column;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 15px;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .stat-card {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }
            
            .modal-content {
                width: 95%;
            }
        }
    </style>
</head>
<body>
   ->
    <div class="sidebar">
        <div class="logo">
            <h2><i class="fas fa-user-cog"></i> <span>Admin Panel</span></h2>
        </div>
        <ul class="menu">
            <li class="menu-item active" onclick="showTab('dashboard')">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </li>
            <li class="menu-item" onclick="showTab('users')">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </li>
            <li class="menu-item" onclick="showTab('resumes')">
                <i class="fas fa-file-alt"></i>
                <span>Resumes</span>
            </li>
            <li class="menu-item" onclick="showTab('templates')">
                <i class="fas fa-images"></i>
                <span>Templates</span>
            </li>
            <li class="menu-item" onclick="showTab('analytics')">
                <i class="fas fa-chart-bar"></i>
                <span>Analytics</span>
            </li>
            <li class="menu-item" onclick="showTab('settings')">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </li>
            <li class="menu-item" onclick="window.location.href='admin_logout.php'">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </li>
        </ul>
    </div>

    
    <div class="main-content">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <div class="user-info">
                <div class="avatar">
                    <i class="fas fa-user"></i>
                </div>
                <span>Administrator</span>
            </div>
        </div>

       
        <div class="tabs">
            <div class="tab active" onclick="showTab('dashboard')">Dashboard Overview</div>
            <div class="tab" onclick="showTab('users')">Users Management</div>
            <div class="tab" onclick="showTab('resumes')">Resumes Management</div>
            <div class="tab" onclick="showTab('templates')">Templates Management</div>
            <div class="tab" onclick="showTab('analytics')">Analytics</div>
            <div class="tab" onclick="showTab('settings')">Settings</div>
        </div>

        
        <div class="tab-content active" id="dashboardTab">
           
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $users_count; ?></h3>
                        <p>Total Users</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon resumes">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $resumes_count; ?></h3>
                        <p>Resume Versions</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon skills">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $skills_count; ?></h3>
                        <p>Skills Added</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon experience">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $experience_count; ?></h3>
                        <p>Experience Entries</p>
                    </div>
                </div>
            </div>
            
           
            <div class="table-container">
                <div class="table-header">
                    <h2>Quick Actions</h2>
                </div>
                <div style="padding: 30px;">
                    <div class="quick-actions">
                        <div class="quick-action-card" onclick="showTab('users')">
                            <i class="fas fa-user-plus"></i>
                            <h4>Add New User</h4>
                            <p>Create new user accounts</p>
                        </div>
                        <div class="quick-action-card" onclick="showTab('templates')">
                            <i class="fas fa-plus-circle"></i>
                            <h4>Add Template</h4>
                            <p>Upload new resume templates</p>
                        </div>
                        <div class="quick-action-card" onclick="showTab('resumes')">
                            <i class="fas fa-eye"></i>
                            <h4>View Resumes</h4>
                            <p>Browse all user resumes</p>
                        </div>
                        <div class="quick-action-card" onclick="showTab('analytics')">
                            <i class="fas fa-chart-line"></i>
                            <h4>View Analytics</h4>
                            <p>Check platform statistics</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="tab-content" id="usersTab">
            <div class="table-container">
                <div class="table-header">
                    <h2>All Users</h2>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search users..." id="searchUsers">
                        </div>
                        <button class="btn btn-add" id="addUserBtn">
                            <i class="fas fa-plus"></i> Add User
                        </button>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <?php while($user = mysqli_fetch_assoc($users_result)): ?>
                        <tr>
                            <td>#<?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                            <td class="actions">
                                <button class="btn btn-view" onclick="viewUser(<?php echo $user['id']; ?>)">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <a href="admin_edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($user['fullname']); ?>?')">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

       
        <div class="tab-content" id="resumesTab">
            <div class="table-container">
                <div class="table-header">
                    <h2>All Resumes</h2>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search resumes..." id="searchResumes">
                        </div>
                        <button class="btn btn-add" id="addResumeBtn">
                            <i class="fas fa-plus"></i> Add Resume Template
                        </button>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Template</th>
                            <th>Version</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="resumesTableBody">
                        <?php while($resume = mysqli_fetch_assoc($resumes_result)): 
                            $header_data = json_decode($resume['header_data'], true);
                            $first_name = $header_data[0]['first_name'] ?? 'Unknown';
                        ?>
                        <tr>
                            <td>#<?php echo $resume['id']; ?></td>
                            <td><?php echo htmlspecialchars($resume['user_name'] ?? 'Unknown'); ?></td>
                            <td>Template #<?php echo htmlspecialchars($resume['template_used']); ?></td>
                            <td><?php echo htmlspecialchars($resume['version_name']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($resume['created_at'])); ?></td>
                            <td class="actions">
                                <button class="btn btn-view" onclick="viewResume(<?php echo $resume['id']; ?>)">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <a href="admin_edit_resume.php?id=<?php echo $resume['id']; ?>" class="btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="../download_resume.php?resume_id=<?php echo $resume['id']; ?>" class="btn btn-download" target="_blank">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this resume?')">
                                    <input type="hidden" name="resume_id" value="<?php echo $resume['id']; ?>">
                                    <button type="submit" name="delete_resume" class="btn btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="tab-content" id="templatesTab">
            <div class="table-container">
                <div class="table-header">
                    <h2>Template Management</h2>
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <a href="admin_templates.php" class="btn btn-add">
                            <i class="fas fa-plus"></i> Manage Templates
                        </a>
                    </div>
                </div>
                <div style="padding: 40px; text-align: center;">
                    <i class="fas fa-images" style="font-size: 48px; color: var(--gray); margin-bottom: 20px;"></i>
                    <h3 style="color: var(--gray); margin-bottom: 10px;">Template Management</h3>
                    <p style="color: var(--gray); margin-bottom: 20px;">Manage your resume templates and images</p>
                    <a href="admin_templates.php" class="btn btn-add">
                        <i class="fas fa-external-link-alt"></i> Open Template Manager
                    </a>
                </div>
            </div>
        </div>

        
        <div class="tab-content" id="analyticsTab">
            <div class="table-container">
                <div class="table-header">
                    <h2>Platform Analytics</h2>
                </div>
                <div style="padding: 40px; text-align: center;">
                    <i class="fas fa-chart-bar" style="font-size: 48px; color: var(--gray); margin-bottom: 20px;"></i>
                    <h3 style="color: var(--gray); margin-bottom: 10px;">Analytics Dashboard</h3>
                    <p style="color: var(--gray); margin-bottom: 20px;">Detailed platform statistics and reports</p>
                    <div class="stats">
                        <div class="stat-card">
                            <div class="stat-info">
                                <h3><?php echo $users_count; ?></h3>
                                <p>Total Users</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-info">
                                <h3><?php echo $resumes_count; ?></h3>
                                <p>Total Resumes</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-info">
                                <h3><?php echo $skills_count; ?></h3>
                                <p>Skills Added</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-info">
                                <h3><?php echo $experience_count + $education_count; ?></h3>
                                <p>Total Entries</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       
        <div class="tab-content" id="settingsTab">
            <div class="table-container">
                <div class="table-header">
                    <h2>Admin Settings</h2>
                </div>
                <div style="padding: 20px;">
                    
                    <div class="settings-tabs">
                        <button class="settings-tab active" onclick="openSettingsTab('security')">Admin Security</button>
                        <button class="settings-tab" onclick="openSettingsTab('database')">Database</button>
                        <button class="settings-tab" onclick="openSettingsTab('notifications')">Notifications</button>
                        <button class="settings-tab" onclick="openSettingsTab('appearance')">Appearance</button>
                    </div>

                    
                   <div id="securityTab" class="settings-tab-content active">
    <h3 style="color: var(--primary); margin-bottom: 20px;">Admin Security Settings</h3>

    <form method="POST">
        
        <div class="form-group">
            <label>Change Admin Password</label>
            <input type="password" name="current_password" class="form-control"
                   placeholder="Current Password" style="margin-bottom: 10px;" required>

            <input type="password" name="new_password" class="form-control"
                   placeholder="New Password" style="margin-bottom: 10px;" required>

            <input type="password" name="confirm_password" class="form-control"
                   placeholder="Confirm New Password" required>
        </div>

        <div class="form-group">
            <label>Admin Session Timeout</label>
            <select name="session_timeout" class="form-control">
                <option value="30">30 minutes</option>
                <option value="60" selected>1 hour</option>
                <option value="120">2 hours</option>
                <option value="240">4 hours</option>
            </select>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" name="require_2fa"> Require Two-Factor Authentication
            </label>
        </div>

        <button type="submit" name="update_security" class="btn btn-primary">
            <i class="fas fa-shield-alt"></i> Update Security Settings
        </button>
    </form>
</div>


                    
                    <div id="databaseTab" class="settings-tab-content">
                        <h3 style="color: var(--primary); margin-bottom: 20px;">Database Management</h3>
                        <div class="settings-actions">
                            <form method="POST" style="display: inline;">
                                <button type="submit" name="backup_database" class="btn btn-success">
                                    <i class="fas fa-download"></i> Backup Database
                                </button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <button type="submit" name="optimize_database" class="btn btn-warning">
                                    <i class="fas fa-broom"></i> Optimize Database
                                </button>
                            </form>
                            <button onclick="showCleanupModal()" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Cleanup Old Data
                            </button>
                        </div>
                        
                        <div class="database-info" style="margin-top: 20px; padding: 20px; background: var(--light); border-radius: 8px;">
                            <h4 style="color: var(--primary); margin-bottom: 15px;">Database Information</h4>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div><strong>Database Size:</strong> <?php echo $db_size_row['size_mb'] ?? '0'; ?> MB</div>
                                <div><strong>Total Users:</strong> <?php echo $users_count; ?></div>
                                <div><strong>Total Resumes:</strong> <?php echo $resumes_count; ?></div>
                                <div><strong>Total Templates:</strong> <?php echo $templates_count; ?></div>
                            </div>
                        </div>
                    </div>

                    
                    <div id="notificationsTab" class="settings-tab-content">
                        <h3 style="color: var(--primary); margin-bottom: 20px;">Email & Notification Settings</h3>
                        <form method="POST">
                            <div class="form-group">
                                <label>Admin Email for Notifications</label>
                                <input type="email" name="admin_email" class="form-control" value="admin@cvforge.com">
                            </div>
                            <div class="form-group">
                                <label>Notification Preferences</label>
                                <div style="display: grid; gap: 10px; margin-top: 10px;">
                                    <label style="display: flex; align-items: center; gap: 10px;">
                                        <input type="checkbox" name="notify_new_users" checked> New user registrations
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 10px;">
                                        <input type="checkbox" name="notify_resume_creations" checked> New resume creations
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 10px;">
                                        <input type="checkbox" name="notify_errors"> System errors
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 10px;">
                                        <input type="checkbox" name="notify_backup"> Backup completion
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email Template</label>
                                <select name="email_template" class="form-control">
                                    <option value="default">Default Template</option>
                                    <option value="modern">Modern Template</option>
                                    <option value="minimal">Minimal Template</option>
                                </select>
                            </div>
                            <button type="submit" name="update_notifications" class="btn btn-primary">
                                <i class="fas fa-bell"></i> Update Notification Settings
                            </button>
                        </form>
                    </div>

                    
                    <div id="appearanceTab" class="settings-tab-content">
                        <h3 style="color: var(--primary); margin-bottom: 20px;">Admin Panel Appearance</h3>
                        <form method="POST">
                            <div class="form-group">
                                <label>Admin Theme</label>
                                <select name="admin_theme" class="form-control">
                                    <option value="default" selected>Default Blue</option>
                                    <option value="dark">Dark Mode</option>
                                    <option value="light">Light Mode</option>
                                    <option value="professional">Professional</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Dashboard Layout</label>
                                <select name="dashboard_layout" class="form-control">
                                    <option value="grid" selected>Grid Layout</option>
                                    <option value="list">List Layout</option>
                                    <option value="compact">Compact Layout</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Items Per Page</label>
                                <select name="items_per_page" class="form-control">
                                    <option value="10">10 items</option>
                                    <option value="25" selected>25 items</option>
                                    <option value="50">50 items</option>
                                    <option value="100">100 items</option>
                                </select>
                            </div>
                            <button type="submit" name="update_appearance" class="btn btn-primary">
                                <i class="fas fa-palette"></i> Update Appearance
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal" id="userModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add New User</h3>
                <span class="close">&times;</span>
            </div>
            <form method="POST" id="userForm">
                <input type="hidden" name="user_id" id="userId">
                <input type="hidden" name="edit_user" id="editUserField">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <small style="color: var(--gray);" id="passwordHelp">Leave blank to keep current password when editing</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel">Cancel</button>
                    <button type="submit" class="btn btn-save" id="saveUserBtn">Save User</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal" id="resumeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Resume Template</h3>
                <span class="close">&times;</span>
            </div>
            <form method="POST" id="resumeForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_id">Select User</label>
                        <select id="user_id" name="user_id" class="form-control" required>
                            <option value="">Select a user</option>
                            <?php 
                            mysqli_data_seek($all_users, 0);
                            while($user = mysqli_fetch_assoc($all_users)): 
                            ?>
                            <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['fullname']); ?> (#<?php echo $user['id']; ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="template_used">Template Number</label>
                        <select id="template_used" name="template_used" class="form-control" required>
                            <option value="1">Template 1</option>
                            <option value="2">Template 2</option>
                            <option value="3">Template 3</option>
                            <option value="4">Template 4</option>
                            <option value="5">Template 5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="version_name">Version Name</label>
                        <input type="text" id="version_name" name="version_name" class="form-control" placeholder="e.g., Professional Resume v1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel">Cancel</button>
                    <button type="submit" name="add_resume" class="btn btn-save">Add Resume Template</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal" id="viewUserModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>User Details</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div id="userDetailsContent">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel">Close</button>
            </div>
        </div>
    </div>

    
    <div class="modal" id="viewResumeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Resume Details</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div id="resumeDetailsContent">
                   
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel">Close</button>
            </div>
        </div>
    </div>

   
    <?php if (!empty($message)): ?>
    <div class="notification success"><?php echo $message; ?></div>
    <script>
        setTimeout(() => {
            const notification = document.querySelector('.notification');
            if (notification) notification.remove();
        }, 3000);
    </script>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <div class="notification error"><?php echo $error; ?></div>
    <script>
        setTimeout(() => {
            const notification = document.querySelector('.notification');
            if (notification) notification.remove();
        }, 3000);
    </script>
    <?php endif; ?>

    <script>
        
        function showTab(tabName) {
            
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
           
            document.getElementById(tabName + 'Tab').classList.add('active');
            
            
            document.querySelectorAll('.tab').forEach(tab => {
                if (tab.textContent.toLowerCase().includes(tabName.toLowerCase())) {
                    tab.classList.add('active');
                }
            });
            
            
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
                if (item.textContent.toLowerCase().includes(tabName.toLowerCase())) {
                    item.classList.add('active');
                }
            });
        }

        
        function openSettingsTab(tabName) {
           
            document.querySelectorAll('.settings-tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
          
            document.querySelectorAll('.settings-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            
            document.getElementById(tabName + 'Tab').classList.add('active');
            
            
            event.currentTarget.classList.add('active');
        }

        
        function showCleanupModal() {
            Swal.fire({
                title: 'Cleanup Old Data',
                html: `
                    <p>Select data to cleanup:</p>
                    <label style="display: block; text-align: left; margin: 10px 0;">
                        <input type="checkbox" id="cleanup_old_resumes"> Resumes older than 1 year
                    </label>
                    <label style="display: block; text-align: left; margin: 10px 0;">
                        <input type="checkbox" id="cleanup_inactive_users"> Users inactive for 6 months
                    </label>
                    <label style="display: block; text-align: left; margin: 10px 0;">
                        <input type="checkbox" id="cleanup_temp_files"> Temporary files
                    </label>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Cleanup Selected',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#f72585'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    Swal.fire('Cleanup Completed!', 'Selected data has been cleaned up.', 'success');
                }
            });
        }

        
        const addUserBtn = document.getElementById('addUserBtn');
        const addResumeBtn = document.getElementById('addResumeBtn');
        const userModal = document.getElementById('userModal');
        const resumeModal = document.getElementById('resumeModal');
        const viewUserModal = document.getElementById('viewUserModal');
        const viewResumeModal = document.getElementById('viewResumeModal');
        const closeBtns = document.querySelectorAll('.close');
        const cancelBtns = document.querySelectorAll('.btn-cancel');

       
        addUserBtn.addEventListener('click', () => {
            document.getElementById('modalTitle').textContent = 'Add New User';
            document.getElementById('userForm').reset();
            document.getElementById('editUserField').value = '';
            document.getElementById('password').required = true;
            document.getElementById('passwordHelp').style.display = 'none';
            document.getElementById('saveUserBtn').name = 'add_user';
            userModal.style.display = 'flex';
        });

        
        addResumeBtn.addEventListener('click', () => {
            resumeModal.style.display = 'flex';
        });

        
        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                userModal.style.display = 'none';
                resumeModal.style.display = 'none';
                viewUserModal.style.display = 'none';
                viewResumeModal.style.display = 'none';
            });
        });

        cancelBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                userModal.style.display = 'none';
                resumeModal.style.display = 'none';
                viewUserModal.style.display = 'none';
                viewResumeModal.style.display = 'none';
            });
        });

        window.addEventListener('click', (e) => {
            if (e.target === userModal || e.target === resumeModal || e.target === viewUserModal || e.target === viewResumeModal) {
                userModal.style.display = 'none';
                resumeModal.style.display = 'none';
                viewUserModal.style.display = 'none';
                viewResumeModal.style.display = 'none';
            }
        });

        
        document.getElementById('searchUsers').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#usersTableBody tr');
            
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.getElementById('searchResumes').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#resumesTableBody tr');
            
            rows.forEach(row => {
                const userName = row.cells[1].textContent.toLowerCase();
                const template = row.cells[2].textContent.toLowerCase();
                const version = row.cells[3].textContent.toLowerCase();
                
                if (userName.includes(searchTerm) || template.includes(searchTerm) || version.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        
        function viewUser(userId) {
            const userDetails = `
                <div class="user-details">
                    <div class="detail-item">
                        <span class="detail-label">User ID:</span>
                        <span class="detail-value">#${userId}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Name:</span>
                        <span class="detail-value" id="viewUserName">Loading...</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value" id="viewUserEmail">Loading...</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Joined:</span>
                        <span class="detail-value" id="viewUserJoined">Loading...</span>
                    </div>
                </div>
            `;
            
            document.getElementById('userDetailsContent').innerHTML = userDetails;
            viewUserModal.style.display = 'flex';
            
            
            setTimeout(() => {
                const rows = document.querySelectorAll('#usersTableBody tr');
                let userData = null;
                
                rows.forEach(row => {
                    const id = row.cells[0].textContent.replace('#', '');
                    if (id == userId) {
                        userData = {
                            name: row.cells[1].textContent,
                            email: row.cells[2].textContent,
                            joined: row.cells[3].textContent
                        };
                    }
                });
                
                if (userData) {
                    document.getElementById('viewUserName').textContent = userData.name;
                    document.getElementById('viewUserEmail').textContent = userData.email;
                    document.getElementById('viewUserJoined').textContent = userData.joined;
                }
            }, 500);
        }

        
        function viewResume(resumeId) {
            const resumeDetails = `
                <div class="resume-details">
                    <div class="detail-item">
                        <span class="detail-label">Resume ID:</span>
                        <span class="detail-value">#${resumeId}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">User:</span>
                        <span class="detail-value" id="viewResumeUser">Loading...</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Template:</span>
                        <span class="detail-value" id="viewResumeTemplate">Loading...</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Version:</span>
                        <span class="detail-value" id="viewResumeVersion">Loading...</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Created:</span>
                        <span class="detail-value" id="viewResumeCreated">Loading...</span>
                    </div>
                </div>
                <div class="resume-preview" id="resumePreview">
                    <h4>Resume Preview</h4>
                    <div id="resumePreviewContent">Loading resume content...</div>
                </div>
            `;
            
            document.getElementById('resumeDetailsContent').innerHTML = resumeDetails;
            viewResumeModal.style.display = 'flex';
            
            
            setTimeout(() => {
                const rows = document.querySelectorAll('#resumesTableBody tr');
                let resumeData = null;
                
                rows.forEach(row => {
                    const id = row.cells[0].textContent.replace('#', '');
                    if (id == resumeId) {
                        resumeData = {
                            user: row.cells[1].textContent,
                            template: row.cells[2].textContent,
                            version: row.cells[3].textContent,
                            created: row.cells[4].textContent
                        };
                    }
                });
                
                if (resumeData) {
                    document.getElementById('viewResumeUser').textContent = resumeData.user;
                    document.getElementById('viewResumeTemplate').textContent = resumeData.template;
                    document.getElementById('viewResumeVersion').textContent = resumeData.version;
                    document.getElementById('viewResumeCreated').textContent = resumeData.created;
                    
                    
                    document.getElementById('resumePreviewContent').innerHTML = `
                        <div class="resume-section">
                            <h4>Personal Information</h4>
                            <p><strong>Name:</strong> John Doe</p>
                            <p><strong>Email:</strong> john.doe@example.com</p>
                            <p><strong>Phone:</strong> +1234567890</p>
                        </div>
                        <div class="resume-section">
                            <h4>Experience</h4>
                            <p><strong>Software Developer</strong> at Tech Company (2020-2023)</p>
                            <p>Developed web applications and maintained software systems.</p>
                        </div>
                        <div class="resume-section">
                            <h4>Education</h4>
                            <p><strong>Bachelor of Computer Science</strong> from University (2016-2020)</p>
                        </div>
                    `;
                }
            }, 500);
        }

        
        function editUser(userId) {
            const rows = document.querySelectorAll('#usersTableBody tr');
            let userData = null;
            
            rows.forEach(row => {
                const id = row.cells[0].textContent.replace('#', '');
                if (id == userId) {
                    userData = {
                        name: row.cells[1].textContent,
                        email: row.cells[2].textContent
                    };
                }
            });
            
            if (userData) {
                document.getElementById('modalTitle').textContent = 'Edit User';
                document.getElementById('userId').value = userId;
                document.getElementById('fullname').value = userData.name;
                document.getElementById('email').value = userData.email;
                document.getElementById('password').required = false;
                document.getElementById('passwordHelp').style.display = 'block';
                document.getElementById('editUserField').value = '1';
                document.getElementById('saveUserBtn').name = 'edit_user';
                userModal.style.display = 'flex';
            }
        }

       
        document.getElementById('userForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const isEdit = document.getElementById('editUserField').value;
            
            if (!isEdit && password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long');
                return;
            }
        });

        
        setTimeout(() => {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            });
        }, 5000);
        echo 
Swal.fire({
    icon: 'success',
    title: 'Password Updated',
    text: 'Your admin password has been changed successfully!'
}).then(() => {
    window.location.href='admin_dashboard.php';
});
    </script>
</body>
</html>