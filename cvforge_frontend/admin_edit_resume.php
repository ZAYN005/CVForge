<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

include 'db_connect.php';

$message = '';
$error = '';


$resume = null;
$user_name = '';
if (isset($_GET['id'])) {
    $resume_id = intval($_GET['id']);
    $stmt = $conn->prepare("
        SELECT rv.*, u.fullname as user_name 
        FROM resume_versions rv 
        LEFT JOIN users u ON rv.user_id = u.id 
        WHERE rv.id = ?
    ");
    $stmt->bind_param("i", $resume_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resume = $result->fetch_assoc();
    $stmt->close();
    
    if ($resume) {
        $user_name = $resume['user_name'];
    } else {
        $error = "Resume not found!";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_resume'])) {
    $resume_id = $_POST['resume_id'];
    $template_used = $_POST['template_used'];
    $version_name = trim($_POST['version_name']);
    
    $stmt = $conn->prepare("UPDATE resume_versions SET template_used = ?, version_name = ? WHERE id = ?");
    $stmt->bind_param("isi", $template_used, $version_name, $resume_id);
    
    if ($stmt->execute()) {
        $message = "Resume updated successfully!";
        
        $resume['template_used'] = $template_used;
        $resume['version_name'] = $version_name;
    } else {
        $error = "Error updating resume!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resume - Admin Dashboard</title>
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
            min-height: 100vh;
        }

        .admin-header {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content h1 {
            color: var(--primary);
        }

        .back-btn {
            background: var(--primary);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .form-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .form-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .form-header h2 {
            color: var(--primary);
            margin-bottom: 10px;
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
            border-radius: 5px;
            font-size: 16px;
            transition: border 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
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

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .notification {
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .notification.success {
            background: var(--success);
        }

        .notification.error {
            background: var(--danger);
        }

        .resume-info {
            background: var(--light);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }

        .info-label {
            font-weight: 600;
            color: var(--dark);
        }

        .info-value {
            color: var(--gray);
        }

        .resume-preview {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .resume-section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }

        .resume-section h4 {
            color: var(--primary);
            margin-bottom: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="header-content">
            <h1><i class="fas fa-user-cog"></i> Admin Dashboard</h1>
            <a href="admin_dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($message)): ?>
        <div class="notification success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
        <div class="notification error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($resume): ?>
        <div class="form-container">
            <div class="form-header">
                <h2>Edit Resume</h2>
                <p>Update resume information and settings</p>
            </div>

            <div class="resume-info">
                <div class="info-item">
                    <span class="info-label">Resume ID:</span>
                    <span class="info-value">#<?php echo $resume['id']; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">User:</span>
                    <span class="info-value"><?php echo htmlspecialchars($user_name); ?> (#<?php echo $resume['user_id']; ?>)</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Created:</span>
                    <span class="info-value"><?php echo date('F j, Y g:i A', strtotime($resume['created_at'])); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Updated:</span>
                    <span class="info-value"><?php echo date('F j, Y g:i A', strtotime($resume['updated_at'])); ?></span>
                </div>
            </div>

            <form method="POST">
                <input type="hidden" name="resume_id" value="<?php echo $resume['id']; ?>">
                
                <div class="form-group">
                    <label for="template_used">Template Number</label>
                    <select id="template_used" name="template_used" class="form-control" required>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo $resume['template_used'] == $i ? 'selected' : ''; ?>>
                            Template <?php echo $i; ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="version_name">Version Name</label>
                    <input type="text" id="version_name" name="version_name" class="form-control" 
                           value="<?php echo htmlspecialchars($resume['version_name']); ?>" required
                           placeholder="e.g., Professional Resume v1">
                </div>

                <div class="form-actions">
                    <a href="admin_dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <a href="../builder.php?edit=<?php echo $resume['id']; ?>" class="btn btn-success" target="_blank">
                        <i class="fas fa-edit"></i> Edit Content
                    </a>
                    <button type="submit" name="update_resume" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Resume
                    </button>
                </div>
            </form>

            <div class="resume-preview">
                <h4>Resume Content Preview</h4>
                <?php
            
                $header_data = json_decode($resume['header_data'], true);
                $experience_data = json_decode($resume['experience_data'], true);
                $education_data = json_decode($resume['education_data'], true);
                $skills_data = json_decode($resume['skills_data'], true);
                ?>

                <?php if ($header_data && !empty($header_data[0])): ?>
                <div class="resume-section">
                    <h4>Personal Information</h4>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($header_data[0]['first_name'] ?? '') . ' ' . htmlspecialchars($header_data[0]['last_name'] ?? ''); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($header_data[0]['email'] ?? ''); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($header_data[0]['phone'] ?? ''); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($header_data[0]['city'] ?? '') . ', ' . htmlspecialchars($header_data[0]['state'] ?? ''); ?></p>
                </div>
                <?php endif; ?>

                <?php if ($experience_data && !empty($experience_data)): ?>
                <div class="resume-section">
                    <h4>Experience</h4>
                    <?php foreach ($experience_data as $exp): ?>
                    <p><strong><?php echo htmlspecialchars($exp['job_title'] ?? ''); ?></strong> at <?php echo htmlspecialchars($exp['company'] ?? ''); ?></p>
                    <p><em><?php echo htmlspecialchars($exp['start_date'] ?? ''); ?> to <?php echo htmlspecialchars($exp['end_date'] ?? 'Present'); ?></em></p>
                    <p><?php echo htmlspecialchars($exp['description'] ?? ''); ?></p>
                    <br>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($education_data && !empty($education_data)): ?>
                <div class="resume-section">
                    <h4>Education</h4>
                    <?php foreach ($education_data as $edu): ?>
                    <p><strong><?php echo htmlspecialchars($edu['degree'] ?? ''); ?></strong> from <?php echo htmlspecialchars($edu['school'] ?? ''); ?></p>
                    <p><em><?php echo htmlspecialchars($edu['start_date'] ?? ''); ?> to <?php echo htmlspecialchars($edu['end_date'] ?? 'Present'); ?></em></p>
                    <br>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (empty($header_data) && empty($experience_data) && empty($education_data)): ?>
                <p>No resume content available. Click "Edit Content" to add information.</p>
                <?php endif; ?>
            </div>

            <div class="action-buttons">
                <a href="../download_resume.php?resume_id=<?php echo $resume['id']; ?>" class="btn btn-success" target="_blank">
                    <i class="fas fa-download"></i> Download PDF
                </a>
                <a href="../builder.php?edit=<?php echo $resume['id']; ?>" class="btn btn-primary" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Open in Builder
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="form-container">
            <div class="form-header">
                <h2>Resume Not Found</h2>
                <p>The requested resume could not be found.</p>
            </div>
            <div style="text-align: center; margin-top: 30px;">
                <a href="admin_dashboard.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>