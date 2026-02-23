<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

include 'db_connect.php';

$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_template'])) {
   
    $template_name = trim($_POST['template_name'] ?? '');
    $template_description = trim($_POST['template_description'] ?? '');
    $template_version = trim($_POST['template_version'] ?? '1.0');

    if (empty($template_name)) {
        $error = "Template name is required.";
    } elseif (empty($template_version)) {
        $error = "Template version is required.";
    } else {
   
        $result = mysqli_query($conn, "SELECT MAX(template_number) as max_number FROM templates");
        $row = mysqli_fetch_assoc($result);
        $next_template_number = $row['max_number'] ? $row['max_number'] + 1 : 1;
        
       
        if (isset($_FILES['template_image']) && $_FILES['template_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'templates/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['template_image']['name'], PATHINFO_EXTENSION);
            $file_name = 'template_' . $next_template_number . '_v' . $template_version . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
           
            $check_stmt = $conn->prepare("SELECT id FROM templates WHERE template_number = ?");
            $check_stmt->bind_param("i", $next_template_number);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $error = "Template number $next_template_number already exists. Please try again.";
            } else {
                
                if (move_uploaded_file($_FILES['template_image']['tmp_name'], $file_path)) {
                    
                    $check_columns = mysqli_query($conn, "SHOW COLUMNS FROM templates LIKE 'template_description'");
                    $has_description = mysqli_num_rows($check_columns) > 0;
                    
                    $check_columns = mysqli_query($conn, "SHOW COLUMNS FROM templates LIKE 'template_version'");
                    $has_version = mysqli_num_rows($check_columns) > 0;
                    
                    if ($has_description && $has_version) {
                        
                        $stmt = $conn->prepare("INSERT INTO templates (template_number, template_name, template_description, template_version, image_path, is_active) VALUES (?, ?, ?, ?, ?, 1)");
                        $stmt->bind_param("issss", $next_template_number, $template_name, $template_description, $template_version, $file_path);
                    } else {
                       
                        $stmt = $conn->prepare("INSERT INTO templates (template_number, template_name, image_path, is_active) VALUES (?, ?, ?, 1)");
                        $stmt->bind_param("iss", $next_template_number, $template_name, $file_path);
                    }
                    
                    if ($stmt->execute()) {
                        $message = "Template added successfully as Template #$next_template_number (v$template_version)!";
                    } else {
                        $error = "Error adding template: " . $stmt->error;
                        
                        unlink($file_path);
                    }
                    $stmt->close();
                } else {
                    $error = "Error uploading file.";
                }
            }
            $check_stmt->close();
        } else {
            $error = "Please select a valid image file.";
        }
    }
}


if (isset($_POST['delete_template'])) {
    $template_id = $_POST['template_id'];
    
  
    $stmt = $conn->prepare("SELECT image_path FROM templates WHERE id = ?");
    $stmt->bind_param("i", $template_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $template = $result->fetch_assoc();
    $stmt->close();
    
    
    if ($template && !empty($template['image_path']) && file_exists($template['image_path'])) {
        unlink($template['image_path']);
    }
    
   
    $stmt = $conn->prepare("DELETE FROM templates WHERE id = ?");
    $stmt->bind_param("i", $template_id);
    
    if ($stmt->execute()) {
        $message = "Template deleted successfully!";
    } else {
        $error = "Error deleting template!";
    }
    $stmt->close();
}


$templates_result = mysqli_query($conn, "SELECT * FROM templates ORDER BY template_number");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Management - CVForge</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }

        h1 {
            color: var(--primary);
            font-size: 32px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-secondary {
            background: var(--gray);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .notification {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .notification.success {
            background: var(--success);
            color: white;
        }

        .notification.error {
            background: var(--danger);
            color: white;
        }

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

        .template-image {
            width: 100px;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--border);
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

        tr:hover {
            background-color: rgba(67, 97, 238, 0.02);
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
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

        .form-text {
            color: var(--gray);
            font-size: 12px;
            margin-top: 5px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="header">
            <h1>Template Management</h1>
            <div class="header-actions">
                <a href="admin_dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="notification success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="notification error"><?php echo $error; ?></div>
        <?php endif; ?>

      
        <div class="table-container">
            <div class="table-header">
                <h2>Add New Template</h2>
            </div>
            <div style="padding: 25px;">
                <form method="POST" enctype="multipart/form-data">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label for="template_name">Template Name *</label>
                            <input type="text" id="template_name" name="template_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="template_version">Template Version *</label>
                            <input type="text" id="template_version" name="template_version" class="form-control" value="1.0" required>
                            <div class="form-text">e.g., 1.0, 2.1, 3.0</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="template_description">Template Description</label>
                        <textarea id="template_description" name="template_description" class="form-control" rows="3" placeholder="Optional description of the template"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="template_image">Template Image *</label>
                        <input type="file" id="template_image" name="template_image" accept="image/*" required>
                        <div class="form-text">Accepted formats: JPG, PNG, GIF, WebP</div>
                    </div>
                    <button type="submit" name="add_template" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Template
                    </button>
                </form>
            </div>
        </div>

      
        <div class="table-container">
            <div class="table-header">
                <h2>All Templates</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Template</th>
                        <th>Version</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($template = mysqli_fetch_assoc($templates_result)): ?>
                    <tr>
                        <td>#<?php echo $template['id']; ?></td>
                        <td>
                            <img src="<?php echo $template['image_path']; ?>" alt="Template" class="template-image" onerror="this.src='https://via.placeholder.com/100x140?text=No+Image'">
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($template['template_name']); ?></strong><br>
                            <small style="color: var(--gray);">
                                <?php echo htmlspecialchars($template['template_description'] ?? 'No description'); ?>
                            </small>
                        </td>
                        <td>
                            <span style="background: var(--primary); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                v<?php echo htmlspecialchars($template['template_version'] ?? '1.0'); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($template['is_active']): ?>
                                <span style="color: var(--success); font-weight: bold;">Active</span>
                            <?php else: ?>
                                <span style="color: var(--danger); font-weight: bold;">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions">
                               
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="template_id" value="<?php echo $template['id']; ?>">
                                    <button type="submit" name="delete_template" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this template?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        
        setTimeout(() => {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>