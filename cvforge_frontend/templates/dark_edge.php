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
  <title>Resume - <?= htmlspecialchars(($h['first_name'] ?? 'Your') . ' ' . ($h['last_name'] ?? 'Name')) ?></title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      color: #333;
      margin: 0;
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
      width: 30%;
      background: #c4500cff;
      color: white;
      padding: 40px 25px;
      display: table-cell;
      vertical-align: top;
    }

    
    .right-column {
      width: 70%;
      padding: 40px 35px;
      display: table-cell;
      vertical-align: top;
    }

   
    .profile-image {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 25px;
      display: block;
    }

    
    .name-role {
      text-align: left;
      margin-bottom: 30px;
    }

    .name-role h1 {
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 8px;
      color: white;
    }

    .name-role p {
      font-size: 16px;
      font-weight: 300;
      color: #ecf0f1;
    }

    
    .contact {
      margin-bottom: 30px;
      text-align: left;
    }

    .contact p:first-child {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 15px;
      color: white;
    }

    .contact p {
      font-size: 14px;
      margin-bottom: 8px;
      color: #ecf0f1;
      line-height: 1.5;
    }

    
    .section-title {
      font-size: 18px;
      font-weight: 600;
      margin: 25px 0 15px 0;
      padding-bottom: 8px;
      text-align: left;
    }

    
    .left-column .section-title {
      border-bottom: 2px solid #34495e;
      color: white;
    }

   
    .right-column .section-title {
      border-bottom: 2px solid #bdc3c7;
      background-color: #76afc7ff
      color: #2c3e50;
    }

    
    .skills ul {
      list-style-type: none;
      padding-left: 0;
      text-align: left;
    }

    .skills li {
      font-size: 14px;
      margin-bottom: 8px;
      color: #ecf0f1;
      line-height: 1.5;
    }

    
    .summary, .experience, .education, .languages {
      margin-bottom: 25px;
      text-align: left;
    }

    
    .summary p {
      font-size: 14px;
      color: #555;
      line-height: 1.6;
      text-align: left;
    }

    
    .job-item {
      margin-bottom: 20px;
    }

    .job-title {
      font-size: 16px;
      font-weight: bold;
      color: #2c3e50;
      margin-bottom: 5px;
    }

    .job-period {
      font-size: 14px;
      color: #7f8c8d;
      margin-bottom: 8px;
    }

    .job-description {
      font-size: 14px;
      color: #555;
      line-height: 1.5;
    }

   
    .edu-item {
      margin-bottom: 15px;
    }

    .edu-degree {
      font-size: 15px;
      font-weight: bold;
      color: #2c3e50;
      margin-bottom: 5px;
    }

    .edu-school {
      font-size: 14px;
      color: #7f8c8d;
    }

    
    .language-item {
      font-size: 14px;
      color: #555;
      margin-bottom: 8px;
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
      
      .right-column {
        padding: 30px 25px;
      }
    }
  </style>
</head>
<body>

  <div class="resume-container">
    
    <div class="left-column">
      <?php if (!empty($h['profile_image'])): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode(file_get_contents(__DIR__ . '/../uploads/' . $h['profile_image'])) ?>" class="profile-image" alt="Profile Picture">
      <?php endif; ?>

      <div class="name-role">
        <h1><?= htmlspecialchars(($h['first_name'] ?? 'Your') . ' ' . ($h['last_name'] ?? 'Name')) ?></h1>
        <p><?= htmlspecialchars($s['headline'] ?? 'Professional Title') ?></p>
      </div>

      <div class="contact">
        <div class="section-title">Contact</div>
        <?php if (!empty($h['email'])): ?>
          <p><?= htmlspecialchars($h['email']) ?></p>
        <?php endif; ?>
        <?php if (!empty($h['phone'])): ?>
          <p><?= htmlspecialchars($h['phone']) ?></p>
        <?php endif; ?>
        <?php if (!empty($h['city']) || !empty($h['state'])): ?>
          <p><?= htmlspecialchars(($h['city'] ?? '') . (!empty($h['city']) && !empty($h['state']) ? ', ' : '') . ($h['state'] ?? '')) ?></p>
        <?php endif; ?>
        <?php if (!empty($h['website'])): ?>
          <p><?= htmlspecialchars($h['website']) ?></p>
        <?php endif; ?>
      </div>

      <?php if (!empty($skills)): ?>
        <div class="skills">
          <div class="section-title">Skills</div>
          <ul>
            <?php foreach ($skills as $sk): ?>
              <li><?= htmlspecialchars($sk['skill_name'] ?? '') ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>

    
    <div class="right-column">
     
      <?php if (!empty($s['about'])): ?>
        <div class="summary">
          <div class="section-title">Summary</div>
          <p><?= htmlspecialchars($s['about']) ?></p>
        </div>
      <?php endif; ?>

      
      <?php if (!empty($experience)): ?>
        <div class="experience">
          <div class="section-title">Work Experience</div>
          <?php foreach ($experience as $exp): ?>
            <div class="job-item">
              <div class="job-title"><?= htmlspecialchars($exp['job_title'] ?? '') ?></div>
              <div class="job-period"><?= htmlspecialchars($exp['company'] ?? '') ?> | <?= htmlspecialchars($exp['start_date'] ?? '') ?> - <?= htmlspecialchars($exp['end_date'] ?? '') ?></div>
              <div class="job-description">
                <?= htmlspecialchars($exp['description'] ?? '') ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      
      <?php if (!empty($education)): ?>
        <div class="education">
          <div class="section-title">Education</div>
          <?php foreach ($education as $edu): ?>
            <div class="edu-item">
              <div class="edu-degree"><?= htmlspecialchars($edu['degree'] ?? '') ?></div>
              <div class="edu-school"><?= htmlspecialchars($edu['school'] ?? '') ?> | <?= htmlspecialchars($edu['start_date'] ?? '') ?> - <?= htmlspecialchars($edu['end_date'] ?? '') ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      
      <?php if (!empty($languages)): ?>
        <div class="languages">
          <div class="section-title">Languages</div>
          <?php foreach ($languages as $lang): ?>
            <div class="language-item"><?= htmlspecialchars($lang['language'] ?? '') ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>