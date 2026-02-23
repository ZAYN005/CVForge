<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}

$header     = $_SESSION['current_header'] ?? [];
$summary    = $_SESSION['current_summary'] ?? [];
$education  = $_SESSION['current_education'] ?? [];
$experience = $_SESSION['current_experience'] ?? [];
$skills     = $_SESSION['current_skills'] ?? [];
$languages  = $_SESSION['current_languages'] ?? [];
$hobbies    = $_SESSION['current_hobbies'] ?? [];

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
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .header-left img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .header-left h1 {
            font-size: 36px;
            color: #6c4f3d;
        }

        .header-left h2 {
            font-size: 18px;
            color: #9e9e9e;
        }

        .header-right {
            text-align: right;
        }

        .header-right p {
            font-size: 14px;
            color: #7f8c8d;
            margin: 5px 0;
        }

        .header-right a {
            color: #6c4f3d;
            text-decoration: none;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #6c4f3d;
            margin-bottom: 10px;
        }

        .section-content {
            font-size: 14px;
            color: #7f8c8d;
        }

        .section ul {
            list-style: none;
            padding: 0;
        }

        .section ul li {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 8px;
        }

        .section .bold {
            font-weight: bold;
            color: #6c4f3d;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-left">
                <?php if (!empty($h['profile_image'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode(file_get_contents(__DIR__ . '/../uploads/' . $h['profile_image'])) ?>" alt="Profile Picture">
                <?php else: ?>
                    <div class="profile-placeholder">No Image</div>
                <?php endif; ?>
                <div>
                    <h1><?= htmlspecialchars(($h['first_name'] ?? 'Your') . ' ' . ($h['last_name'] ?? 'Name')) ?></h1>
                    <h2><?= htmlspecialchars($s['headline'] ?? 'Sales Associate') ?></h2>
                </div>
            </div>
            <div class="header-right">
                <p><?= htmlspecialchars($h['phone'] ?? '123-456-7890') ?> | <?= htmlspecialchars($h['email'] ?? 'hello@reallygreatsite.com') ?></p>
               <p><?= htmlspecialchars($h['city'] ?? 'Anywhere St.') ?>, <?= htmlspecialchars($h['state'] ?? 'Any City') ?></p>
            </div>
        </header>
        <div class="section">
            <div class="section-title">SUMMARY</div>
            <div class="section-content">
                <?php if (!empty($s['objective'])): ?>
                    <p><strong>Career Objective:</strong> <?= htmlspecialchars($s['objective']) ?></p>
                <?php endif; ?>
                <?php if (!empty($s['strengths'])): ?>
                    <p><strong>Strengths:</strong> <?= htmlspecialchars($s['strengths']) ?></p>
                <?php endif; ?>
                <?php if (!empty($s['soft_skills'])): ?>
                    <p><strong>Soft Skills:</strong> <?= htmlspecialchars($s['soft_skills']) ?></p>
                <?php endif; ?>
                <?php if (!empty($s['achievements'])): ?>
                    <p><strong>Achievements:</strong> <?= htmlspecialchars($s['achievements']) ?></p>
                <?php endif; ?>
                <?php if (!empty($s['hobbies'])): ?>
                <div class="summary-item">
                <i class="bi bi-palette-fill bi-icon"></i>
                <div><strong>Hobbies:</strong> <?= htmlspecialchars($s['hobbies']) ?></div>
              </div>
           <?php endif; ?>
         </div>
        </div>

       
        <div class="section">
            <div class="section-title">EDUCATION</div>
            <div class="section-content">
                <ul>
                    <?php foreach ($education as $edu): ?>
                        <li><span class="bold"><?= htmlspecialchars($edu['degree'] ?? 'Degree') ?></span> – <?= htmlspecialchars($edu['school'] ?? 'School') ?> <br> <?= htmlspecialchars($edu['start_date'] ?? 'Start Date') ?> – <?= htmlspecialchars($edu['end_date'] ?? 'End Date') ?> | IPK: <?= htmlspecialchars($edu['gpa'] ?? 'GPA') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
<?php if (!empty($experience)): ?>
    <section class="section experience">
        <div class="section-title">WORK EXPERIENCE</div>
        <?php foreach ($experience as $exp): ?>
            <div class="experience-item">
                
                <div class="job-period"><?= htmlspecialchars($exp['start_date'] ?? '') ?> – <?= htmlspecialchars($exp['end_date'] ?? '') ?></div>
                
                
                <div class="job-title"><?= htmlspecialchars($exp['job_title'] ?? '') ?></div>
                
                
                <div class="company"><?= htmlspecialchars($exp['company'] ?? '') ?></div>

                
                <div class="job-description">
                    <i class="bi bi-briefcase-fill bi-icon"></i>
                    <?php 
                    $description = $exp['description'] ?? ''; 
                    
                    $bulletPoints = explode('.', $description); 
                    ?>
                    <ul>
                        <?php foreach ($bulletPoints as $point): ?>
                            <?php $point = trim($point); ?>
                            <?php if (!empty($point)): ?>
                                <li><?= htmlspecialchars($point) ?>.</li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

         <?php if (!empty($skills)): ?>
    <div class="skills">
      <div class="section-title">SKILLS</div>
      <?php foreach ($skills as $sk): ?>
        <div class="skill-item"><i class="bi bi-lightning-charge-fill bi-icon"></i> <?= htmlspecialchars($sk['skill_name'] ?? '') ?></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

        <div class="footer">
            <p>© 2025 <?= htmlspecialchars(($h['first_name'] ?? 'Olivia') . ' ' . ($h['last_name'] ?? 'Wilson')) ?>. All rights reserved.</p>
        </footer>
        </div>
    </div>
</body>
</html>

