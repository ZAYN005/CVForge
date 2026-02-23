<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$header = $_SESSION['current_header'] ?? [];
$summary = $_SESSION['current_summary'] ?? [];
$experience = $_SESSION['current_experience'] ?? []; 
$education = $_SESSION['current_education'] ?? [];
$skills = $_SESSION['current_skills'] ?? [];


$h = !empty($header) ? $header[0] : [];
$s = !empty($summary) ? $summary[0] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($h['first_name'] ?? 'Olivia') . ' ' . ($h['last_name'] ?? 'Wilson')) ?> - Resume</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
       
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: orange;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        
        .header {
            text-align: left;
            margin-bottom: 0px;
            padding-bottom: 0px;
            border-bottom: 0;
        }

        .name {
            font-size: 2.2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0;
            letter-spacing: 1px;
        }


        .divider {
            height: 1px;
            background-color: 0;
            margin: 10px 0;
        }

        
        .section {
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 1.4em;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #2c5aa0;
        }

        
        .summary p {
            color: #555;
            font-size: 1.15em;
            text-align: justify;
        }

.summary {
    background-color: #8dc8e6ff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.summary .section-title {
    font-size: 25px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #2c5aa0; 
    border-bottom: 2px solid #2980b9;
    padding-bottom: 5px;
}


.summary p strong {
    color: #2c5aa0; 
}

.summary p {
    font-size: 16px;
    line-height: 1.6;
    color: #2c3e50; 
    margin-bottom: 10px;
}

.summary ul {
    margin-top: 10px;
    margin-left: 20px;
}

.summary ul li {
    font-size: 16px;
    color: #7f8c8d; 
    margin-bottom: 10px;
    list-style-type: disc;
    margin-left: 20px;
}

.summary .summary-item {
    margin-bottom: 15px;
}

.summary .summary-item i {
    color: #2980b9;
    margin-right: 10px;
    font-size: 18px;
}

.summary .summary-item div {
    font-size: 16px;
    color: #7f8c8d;
}


        
        .experience-item, .education-item {
            margin-bottom: 10px;
        }

        .job-title, .degree {
            font-weight: bold;
            color: #2c5aa0;
            font-size: 1.15em;
            margin-bottom: 5px;
        }

        .company, .institution {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }

        .job-date, .education-date {
            color: #777;
            font-style: italic;
            margin-bottom: 5px;
        }

        .job-description {
            margin-top: 10px;
            color: #555;
        }

        .job-description ul {
            padding-left: 20px;
        }

        .job-description li {
            margin-bottom: 8px;
        }

       
        .education-details {
            margin-top: 5px;
            color: #555;
        }

        .education-details ul {
            padding-left: 20px;
        }

        .education-details li {
            margin-bottom: 5px;
        }

.contact-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 20px;
}

.contact-info p {
    font-size: 14px;
    color: #7f8c8d;
    margin-bottom: 8px;
}

.contact-info p span {
    color: #2c3e50;
    font-weight: bold;
}

.contact-info i {
    margin-right: 10px;
    color: #2c3e50;
}

        
        .skills {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px;
            margin-top: 5px;
        }

        .skill {
            padding: 8px 0;
            color: #555;
        }

        .skill:before {
            content: "•";
            color: #2c5aa0;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }

       
        footer {
            text-align: center;
            margin-top: 10px;
            font-size: 0.9em;
            color: #777;
        }

        
        @media (max-width: 768px) {
            .container {
                padding: 25px;
            }

            .contact-info {
                gap: 5px;
            }

            .skills {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="header"></div>
<div class="container">
    <header>
        <h1 class="name"><?= htmlspecialchars(($h['first_name'] ?? 'Your') . ' ' . ($h['last_name'] ?? 'Name')) ?></h1>
        <h2 class="title"><?= htmlspecialchars($s['headline'] ?? 'Professional Title') ?></h2>
    </header>
</div>

    <div class="contact-info">
    <?php if (!empty($h['email'])): ?>
        <p><i class="bi bi-envelope-fill"></i><span>Email:</span> <?= htmlspecialchars($h['email']) ?></p>
    <?php endif; ?>
    <?php if (!empty($h['phone'])): ?>
        <p><i class="bi bi-telephone-fill"></i><span>Phone:</span> <?= htmlspecialchars($h['phone']) ?></p>
    <?php endif; ?>
    <?php if (!empty($h['city']) || !empty($h['state'])): ?>
        <p><i class="bi bi-geo-alt-fill"></i><span>Location:</span> <?= htmlspecialchars(($h['city'] ?? '') . (!empty($h['city']) && !empty($h['state']) ? ', ' : '') . ($h['state'] ?? '')) ?></p>
    <?php endif; ?>
    <?php if (!empty($h['website'])): ?>
        <p><i class="bi bi-globe2"></i><span>Website:</span> <?= htmlspecialchars($h['website']) ?></p>
    <?php endif; ?>
</div>


 <section class="summary">
      <div class="section-title">Summary</div>
      <?php if (!empty($s['objective'])): ?>
      <div class="summary-item">
        <i class="bi bi-bullseye bi-icon"></i>
        <div><strong>Career Objective:</strong> <?= htmlspecialchars($s['objective']) ?></div>
      </div>
      <?php endif; ?>
      <?php if (!empty($s['strengths'])): ?>
      <div class="summary-item">
        <i class="bi bi-activity bi-icon"></i>
        <div><strong>Strengths:</strong> <?= htmlspecialchars($s['strengths']) ?></div>
      </div>
      <?php endif; ?>
      <?php if (!empty($s['achievements'])): ?>
      <div class="summary-item">
        <i class="bi bi-trophy-fill bi-icon"></i>
        <div><strong>Achievements:</strong> <?= htmlspecialchars($s['achievements']) ?></div>
      </div>
      <?php endif; ?>
      <?php if (!empty($s['hobbies'])): ?>
      <div class="summary-item">
        <i class="bi bi-palette-fill bi-icon"></i>
        <div><strong>Hobbies:</strong> <?= htmlspecialchars($s['hobbies']) ?></div>
      </div>
      <?php endif; ?>
    </section>
  </div>
        <div class="divider"></div>

      
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
<?php else: ?>
    <p>No work experience available.</p>
<?php endif; ?>
        </section>

        <div class="divider"></div>

       
        <section class="section education">
            <div class="section-title">EDUCATION</div>
            <?php if (!empty($education)): ?>
                <?php foreach ($education as $edu): ?>
                    <div class="education-item">
                        <div class="degree"><?= htmlspecialchars($edu['degree'] ?? '') ?></div>
                        <div class="institution"><?= htmlspecialchars($edu['school'] ?? '') ?></div>
                        <div class="education-date"><?= htmlspecialchars($edu['start_date'] ?? '') ?> - <?= htmlspecialchars($edu['end_date'] ?? '') ?></div>
                        <?php if (!empty($edu['details'])): ?>
                            <div class="education-details">
                                <ul>
                                    <?php 
                                    $details = explode(',', $edu['details'] ?? '');
                                    foreach ($details as $detail):
                                        $detail = trim($detail);
                                        if (!empty($detail)):
                                    ?>
                                        <li><?= htmlspecialchars($detail) ?></li>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No education information available.</p>
            <?php endif; ?>
        </section>

        <div class="divider"></div>

       
        <section class="section skills-section">
            <div class="section-title">KEY SKILLS</div>
            <div class="skills">
                <?php if (!empty($skills)): ?>
                    <?php foreach ($skills as $skill): ?>
                        <div class="skill"><?= htmlspecialchars($skill['skill_name'] ?? '') ?></div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="skill">No skills listed</div>
                <?php endif; ?>
            </div>
        </section>

        <footer>
            <p>© 2025 <?= htmlspecialchars(($h['first_name'] ?? 'Olivia') . ' ' . ($h['last_name'] ?? 'Wilson')) ?>. All rights reserved.</p>
        </footer>
    </div>

</body>
</html>
