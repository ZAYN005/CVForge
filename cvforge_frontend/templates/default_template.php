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
$experience = $_SESSION['current_experiences'] ?? [];
$skills     = $_SESSION['current_skills'] ?? [];
$languages  = $_SESSION['current_languages'] ?? [];
$h = $header[0] ?? [];
$s = $summary[0] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars(($h['first_name'] ?? 'Your') . ' ' . ($h['last_name'] ?? 'Name')) ?> - Resume</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f5f5f5;
  padding: 20px;
}

.resume-container {
  width: 210mm;
  min-height: 297mm;
  margin: 0 auto;
  background: white;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  display: table;
  border-collapse: collapse;
}

.left-column {
  width: 35%;
  background: #2c3e50;
  color: white;
  padding: 40px 25px;
  display: table-cell;
  vertical-align: top;
}

.right-column {
  width: 65%;
  padding: 40px 50px;
  display: table-cell;
  vertical-align: top;
}


.profile-section {
  text-align: center;
  margin-bottom: 30px;
  padding-bottom: 25px;
  border-bottom: 2px solid #f39c12;
}

.profile-img {
  width: 160px;
  height: 160px;
  border-radius: 50%;
  border: 5px solid white;
  margin: 0 auto 15px;
  display: block;
  object-fit: cover;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.profile-placeholder {
  width: 160px;
  height: 160px;
  border-radius: 50%;
  border: 5px solid white;
  margin: 0 auto 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f39c12;
  color: white;
  font-size: 14px;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.section-title {
  color: #f39c12;
  font-size: 18px;
  margin: 30px 0 20px 0;
  border-bottom: 2px solid #f39c12;
  padding-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  font-weight: 600;
}


.bi-icon {
  width: 20px;
  margin-right: 12px;
  text-align: center;
  color: #f39c12;
  font-size: 16px;
  flex-shrink: 0;
}

.contact-info p, .education-item, .skill-item, .language-item, .summary-item {
  margin: 12px 0;
  font-size: 14px;
  display: flex;
  align-items: flex-start;
  line-height: 1.5;
}

.name {
  color: #2c3e50;
  font-size: 36px;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-weight: 700;
}

.title {
  color: #7f8c8d;
  font-size: 20px;
  margin-bottom: 35px;
  font-weight: 400;
  border-left: 4px solid #f39c12;
  padding-left: 15px;
}

.right-column .section-title {
  color: #2c3e50;
  border-bottom: 2px solid #f39c12;
  font-size: 18px;
  margin: 30px 0 20px 0;
  padding-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  font-weight: 600;
}

.job-item {
  margin: 25px 0;
  padding-left: 0;
  position: relative;
}

.job-period {
  color: #666;
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 8px;
  background: #f8f9fa;
  padding: 4px 12px;
  border-radius: 4px;
  display: inline-block;
}

.job-title {
  color: #2c3e50;
  font-size: 17px;
  font-weight: 700;
  margin: 8px 0 4px 0;
}

.job-company {
  color: #34495e;
  font-weight: 600;
  margin-bottom: 10px;
  font-size: 15px;
}

.job-description {
  margin: 10px 0;
  font-size: 14px;
  line-height: 1.6;
  color: #555;
  padding-left: 32px;
}

.summary-item {
  margin: 12px 0;
  font-size: 14px;
  line-height: 1.6;
  display: flex;
  align-items: flex-start;
}

.summary-item strong {
  color: #2c3e50;
  min-width: 150px;
  display: inline-block;
  font-weight: 600;
}


.education-item .bi-icon {
  margin-top: 2px;
}

.education-content {
  flex: 1;
}

.education-content strong {
  color: #f39c12;
  display: block;
  margin-bottom: 4px;
}

.education-content small {
  color: #bdc3c7;
  font-size: 12px;
}


.skill-item, .language-item {
  padding: 6px 0;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}

.skill-item:last-child, .language-item:last-child {
  border-bottom: none;
}


.contact-info p:empty, .education-item:empty, .skill-item:empty, .language-item:empty {
  display: none;
}
</style>
</head>
<body>
<div class="resume-container">
  
 
  <div class="left-column">
    <?php if (!empty($h['profile_image'])): ?>
      <img src="data:image/jpeg;base64,<?= base64_encode(file_get_contents(__DIR__ . '/../uploads/' . $h['profile_image'])) ?>" class="profile-img" alt="Profile">
    <?php endif; ?>
    <div class="contact-info">
      <div class="section-title">CONTACT</div>
      <?php if (!empty($h['email'])): ?>
        <p><i class="bi bi-envelope-fill bi-icon"></i> <?= htmlspecialchars($h['email']) ?></p>
      <?php endif; ?>
      <?php if (!empty($h['phone'])): ?>
        <p><i class="bi bi-telephone-fill bi-icon"></i> <?= htmlspecialchars($h['phone']) ?></p>
      <?php endif; ?>
      <?php if (!empty($h['city']) || !empty($h['state'])): ?>
        <p><i class="bi bi-geo-alt-fill bi-icon"></i> <?= htmlspecialchars(($h['city'] ?? '') . (!empty($h['city']) && !empty($h['state']) ? ', ' : '') . ($h['state'] ?? '')) ?></p>
      <?php endif; ?>
      <?php if (!empty($h['website'])): ?>
        <p><i class="bi bi-globe2 bi-icon"></i> <?= htmlspecialchars($h['website']) ?></p>
      <?php endif; ?>
    </div>

    <?php if (!empty($education)): ?>
    <div class="education">
      <div class="section-title">EDUCATION</div>
      <?php foreach ($education as $edu): ?>
        <div class="education-item">
          <i class="bi bi-mortarboard-fill bi-icon"></i>
          <div class="education-content">
            <strong><?= htmlspecialchars($edu['degree'] ?? '') ?></strong>
            <?= htmlspecialchars($edu['school'] ?? '') ?><br>
            <small><?= htmlspecialchars($edu['start_date'] ?? '') ?> - <?= htmlspecialchars($edu['end_date'] ?? '') ?></small>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($skills)): ?>
    <div class="skills">
      <div class="section-title">SKILLS</div>
      <?php foreach ($skills as $sk): ?>
        <div class="skill-item"><i class="bi bi-lightning-charge-fill bi-icon"></i> <?= htmlspecialchars($sk['skill_name'] ?? '') ?></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($languages)): ?>
    <div class="languages">
      <div class="section-title">LANGUAGES</div>
      <?php foreach ($languages as $lang): ?>
        <div class="language-item"><i class="bi bi-translate bi-icon"></i> <?= htmlspecialchars($lang['language'] ?? '') ?></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>


  <div class="right-column">
    <header>
      <h1 class="name"><?= htmlspecialchars(($h['first_name'] ?? 'Your') . ' ' . ($h['last_name'] ?? 'Name')) ?></h1>
      <h2 class="title"><?= htmlspecialchars($s['headline'] ?? 'Professional Title') ?></h2>
    </header>

    <?php if (!empty($s['about'])): ?>
    <section class="about">
      <div class="section-title">About Me</div>
      <p><?= htmlspecialchars($s['about']) ?></p>
    </section>
    <?php endif; ?>

    <?php if (!empty($experience)): ?>
    <section class="experience">
      <div class="section-title">Work Experience</div>
      <?php foreach ($experience as $exp): ?>
        <div class="job-item">
          <div class="job-period"><?= htmlspecialchars($exp['start_date'] ?? '') ?> – <?= htmlspecialchars($exp['end_date'] ?? '') ?></div>
          <div class="job-title"><?= htmlspecialchars($exp['job_title'] ?? '') ?></div>
          <div class="job-company"><?= htmlspecialchars($exp['company'] ?? '') ?></div>
          <div class="job-description">
            <i class="bi bi-briefcase-fill bi-icon"></i>
            <?= htmlspecialchars($exp['description'] ?? '') ?>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
    <?php endif; ?>

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
      <?php if (!empty($s['soft_skills'])): ?>
      <div class="summary-item">
        <i class="bi bi-people-fill bi-icon"></i>
        <div><strong>Soft Skills:</strong> <?= htmlspecialchars($s['soft_skills']) ?></div>
      </div>
      <?php endif; ?>
      <?php if (!empty($s['achievements'])): ?>
      <div class="summary-item">
        <i class="bi bi-trophy-fill bi-icon"></i>
        <div><strong>Achievements:</strong> <?= htmlspecialchars($s['achievements']) ?></div>
      </div>
      <?php endif; ?>
      <?php if (!empty($s['projects'])): ?>
      <div class="summary-item">
        <i class="bi bi-rocket-takeoff-fill bi-icon"></i>
        <div><strong>Projects:</strong> <?= htmlspecialchars($s['projects']) ?></div>
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

</div>
</body>
</html>
