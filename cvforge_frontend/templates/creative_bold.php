<?php
if (session_status() === PHP_SESSION_NONE){
  session_start();
}

include dirname(__DIR__) . '/db_connect.php';

$user_id = $_SESSION['user_id'] ?? 1;

function fetchData($conn, $table, $user_id) {
  $rows = [];
  $res = mysqli_query($conn, "SELECT * FROM $table WHERE user_id='$user_id'");
  while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
  return $rows;
}



$header     = $_SESSION['current_header'] ?? [];
$summary    = $_SESSION['current_summary'] ?? [];
$education  = $_SESSION['current_education'] ?? [];
$experience = $_SESSION['current_experience'] ?? [];
$skills     = $_SESSION['current_skills'] ?? [];
$languages  = $_SESSION['current_languages'] ?? [];
$h = $header[0] ?? [];
$s = $summary[0] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($h['first_name'] ?? 'Your') . ' ' . ($h['last_name'] ?? 'Name')) ?> - Resume</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        
        .resume-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            display: table;
            border-collapse: collapse;
        }
        
        
        .left-column {
            width: 35%;
            background-color: #9f5dd9ff;
            color: white;
            padding: 40px 30px;
            display: table-cell;
            vertical-align: top;
        }
        
        
        .right-column {
            width: 65%;
            background-color: white;
            padding: 40px 30px;
            display: table-cell;
            vertical-align: top;
        }
        
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 30px;
            display: block;
            border: 3px solid #e67e22;
        }
        
        .profile-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #e67e22;
            margin: 0 auto 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 60px;
            color: white;
            border: 3px solid white;
        }
        
        .name-title {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .name-title h1 {
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 700;
            color: white;
        }
        
        .name-title h2 {
            font-size: 18px;
            color: #e67e22;
            font-weight: 500;
        }
        
       
        .section-title {
            font-size: 18px;
            color: #e68422ff;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e67e22;
        }
        
        .contact-info {
            margin-bottom: 30px;
        }
        
        .contact-item {
            margin-bottom: 12px;
        }
        
        .skills-list, .languages-list {
            list-style-type: none;
            margin-bottom: 30px;
        }
        
        .skills-list li, .languages-list li {
            margin-bottom: 10px;
            padding-left: 15px;
            position: relative;
        }
        
        .skills-list li:before, .languages-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #e67e22;
            font-size: 18px;
        }
        
        
        .right-section-title {
            font-size: 18px;
            color: #a82a2aff;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e67e22;
        }
        
        .profile-text {
            margin-bottom: 30px;
            line-height: 1.6;
            color: #555;
        }
        
        .education-item, .experience-item {
            margin-bottom: 25px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .date-range {
            color: #e67e22;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 15px;
        }
        
        .institution, .company {
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
            font-size: 16px;
        }
        
        .role {
            color: #e67e22;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 15px;
        }
        
        .description {
            color: #666;
            line-height: 1.5;
        }
        
        
        .summary-item {
            margin-bottom: 15px;
            padding: 12px;
            background: white;
            border-radius: 6px;
        }
        
        .summary-item strong {
            color: #2c3e50;
        }
        
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .resume-container {
                width: 100%;
                display: block;
            }
            
            .left-column, .right-column {
                width: 100%;
                display: block;
            }
            
            .left-column {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="resume-container">
        
       <div class="left-column">
    <?php if (!empty($h['profile_image'])): ?>
        <?php
        $imagePath = __DIR__ . '/../uploads/' . $h['profile_image'];
        if (file_exists($imagePath)): ?>
            <img src="data:image/jpeg;base64,<?= base64_encode(file_get_contents($imagePath)) ?>" class="profile-image" alt="Profile Picture">
        <?php else: ?>
            <div class="profile-placeholder">
                <?php
                $initials = '';
                if (!empty($h['first_name'])) {
                    $initials .= substr($h['first_name'], 0, 1);
                }
                if (!empty($h['last_name'])) {
                    $initials .= substr($h['last_name'], 0, 1);
                }
                echo !empty($initials) ? $initials : 'RS';
                ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="profile-placeholder">
            <?php
            $initials = '';
            if (!empty($h['first_name'])) {
                $initials .= substr($h['first_name'], 0, 1);
            }
            if (!empty($h['last_name'])) {
                $initials .= substr($h['last_name'], 0, 1);
            }
            echo !empty($initials) ? $initials : 'RS';
            ?>
        </div>
    <?php endif; ?>
            
            
            <div class="name-title">
                <h1><?= htmlspecialchars(($h['first_name'] ?? 'Your') . ' ' . ($h['last_name'] ?? 'Name')) ?></h1>
                <h2><?= htmlspecialchars($s['headline'] ?? 'Professional Title') ?></h2>
            </div>
            
            
            <div class="contact-info">
                <h3 class="section-title">CONTACT</h3>
                <?php if (!empty($h['phone'])): ?>
                <div class="contact-item"><?= htmlspecialchars($h['phone']) ?></div>
                <?php endif; ?>
                
                <?php if (!empty($h['email'])): ?>
                <div class="contact-item"><?= htmlspecialchars($h['email']) ?></div>
                <?php endif; ?>
                
                <?php if (!empty($h['website'])): ?>
                <div class="contact-item"><?= htmlspecialchars($h['website']) ?></div>
                <?php endif; ?>
                
                <?php if (!empty($h['city']) || !empty($h['state'])): ?>
                <div class="contact-item"><?= htmlspecialchars(($h['city'] ?? '') . (!empty($h['city']) && !empty($h['state']) ? ', ' : '') . ($h['state'] ?? '')) ?></div>
                <?php endif; ?>
            </div>
            
            
            <?php if (!empty($skills)): ?>
            <div class="skills-section">
                <h3 class="section-title">SKILLS</h3>
                <ul class="skills-list">
                    <?php foreach ($skills as $sk): ?>
                    <li><?= htmlspecialchars($sk['skill_name'] ?? '') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            
            <?php if (!empty($languages)): ?>
            <div class="languages-section">
                <h3 class="section-title">LANGUAGES</h3>
                <ul class="languages-list">
                    <?php foreach ($languages as $lang): ?>
                    <li><?= htmlspecialchars($lang['language'] ?? '') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
        
       
        <div class="right-column">
          
            <?php if (!empty($s['about'])): ?>
            <div class="profile-section">
                <h3 class="right-section-title">PROFILE</h3>
                <p class="profile-text"><?= htmlspecialchars($s['about']) ?></p>
            </div>
            <?php endif; ?>
            
            
            <?php if (!empty($education)): ?>
            <div class="education-section">
                <h3 class="right-section-title">EDUCATION</h3>
                <?php foreach ($education as $edu): ?>
                <div class="education-item">
                    <div class="date-range"><?= htmlspecialchars($edu['start_date'] ?? '') ?> - <?= htmlspecialchars($edu['end_date'] ?? '') ?></div>
                    <div class="institution"><?= htmlspecialchars($edu['school'] ?? '') ?></div>
                    <div class="role"><?= htmlspecialchars($edu['degree'] ?? '') ?></div>
                    <?php if (!empty($edu['description'])): ?>
                    <div class="description"><?= htmlspecialchars($edu['description']) ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            
            <?php if (!empty($experience)): ?>
            <div class="experience-section">
                <h3 class="right-section-title">EXPERIENCE</h3>
                <?php foreach ($experience as $exp): ?>
                <div class="experience-item">
                    <div class="date-range"><?= htmlspecialchars($exp['start_date'] ?? '') ?> - <?= htmlspecialchars($exp['end_date'] ?? '') ?></div>
                    <div class="company"><?= htmlspecialchars($exp['company'] ?? '') ?></div>
                    <div class="role"><?= htmlspecialchars($exp['job_title'] ?? '') ?></div>
                    <?php if (!empty($exp['description'])): ?>
                    <div class="description"><?= htmlspecialchars($exp['description']) ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            
            <?php if (!empty($s['objective']) || !empty($s['strengths']) || !empty($s['soft_skills']) || !empty($s['achievements']) || !empty($s['projects']) || !empty($s['hobbies'])): ?>
            <div class="summary-section">
                <h3 class="right-section-title">SUMMARY</h3>
                
                <?php if (!empty($s['objective'])): ?>
                <div class="summary-item">
                    <strong>Career Objective:</strong> <?= htmlspecialchars($s['objective']) ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($s['strengths'])): ?>
                <div class="summary-item">
                    <strong>Strengths:</strong> <?= htmlspecialchars($s['strengths']) ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($s['soft_skills'])): ?>
                <div class="summary-item">
                    <strong>Soft Skills:</strong> <?= htmlspecialchars($s['soft_skills']) ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($s['achievements'])): ?>
                <div class="summary-item">
                    <strong>Achievements:</strong> <?= htmlspecialchars($s['achievements']) ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($s['projects'])): ?>
                <div class="summary-item">
                    <strong>Projects:</strong> <?= htmlspecialchars($s['projects']) ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($s['hobbies'])): ?>
                <div class="summary-item">
                    <strong>Hobbies:</strong> <?= htmlspecialchars($s['hobbies']) ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>