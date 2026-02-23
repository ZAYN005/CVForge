<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../cvforge_api/db_connect.php';

$user_id = $_SESSION['user_id'] ?? 1;


$template_id = $_SESSION['selected_template'] ?? 1; 

$template_map = [
    1 => "templates/default_template.php",
    2 => "templates/creative_bold.php",
    3 => "templates/classic_elegance.php",
    4 => "templates/dark_edge.php",
    5 => "templates/creative.php",
    6 => "templates/standard.php"
];

$selected_template_path = $template_map[$template_id] ?? $template_map[1];


require_once __DIR__ . '/vendor/autoload.php';
use Dompdf\Dompdf;

function saveResumeToDatabase($conn, $user_id, $template_used) {
   
    $resume_data = [
        'header' => $_SESSION['current_header'] ?? [],
        'experience' => $_SESSION['current_experience'] ?? [],
        'education' => $_SESSION['current_education'] ?? [],
        'skills' => $_SESSION['current_skills'] ?? [],
        'languages' => $_SESSION['current_languages'] ?? [],
        'summary' => $_SESSION['current_summary'] ?? []
    ];
    
    
    $has_minimum_data = !empty($resume_data['header']);
    
    if ($has_minimum_data && $conn) {
        try {
            
            $sql = "INSERT INTO resume_versions (user_id, template_used, header_data, experience_data, education_data, skills_data, languages_data, summary_data) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            
$header_json = json_encode($resume_data['header']);
$experience_json = json_encode($resume_data['experience']);
$education_json = json_encode($resume_data['education']);
$skills_json = json_encode($resume_data['skills']);
$languages_json = json_encode($resume_data['languages']);
$summary_json = json_encode($resume_data['summary']);

$stmt->bind_param("isssssss", 
    $user_id,
    $template_used,
    $header_json,      
    $experience_json,  
    $education_json,   
    $skills_json,      
    $languages_json,   
    $summary_json      
);
            
            $stmt->execute();
            $stmt->close();
            
            
            $update_sql = "UPDATE users SET has_completed_resume = TRUE WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user_id);
            $update_stmt->execute();
            $update_stmt->close();
            
            return true;
        } catch (Exception $e) {
            error_log("Error saving resume: " . $e->getMessage());
            return false;
        }
    }
    
    return false;
}

if (isset($_GET['download'])) {
  
    $header = $_SESSION['current_header'] ?? [];
    $experience = $_SESSION['current_experience'] ?? [];
    $education = $_SESSION['current_education'] ?? [];
    $languages = $_SESSION['current_languages'] ?? [];
    $skills = $_SESSION['current_skills'] ?? [];
    $summary = $_SESSION['current_summary'] ?? [];
    
  
    $template_id = $_SESSION['selected_template_id'] ?? 1;
    
    
    error_log("Download - Using template ID: " . $template_id);
    error_log("Download - Session selected_template_id: " . ($_SESSION['selected_template_id'] ?? 'not set'));
    
   
    $template_files = [
        1 => "templates/default_template.php",
        2 => "templates/creative_bold.php", 
        3 => "templates/classic_elegance.php",
        4 => "templates/dark_edge.php",
        5 => "templates/creative.php",
        6 => "templates/standard.php"
    ];
    
    $selected_template = $template_files[$template_id] ?? $template_files[1];
    
    
    error_log("Download - Loading template: " . $selected_template);
    
  
    $template_path = __DIR__ . '/' . $selected_template;
    if (!file_exists($template_path)) {
        error_log("ERROR: Template file not found: " . $template_path);
        
        $selected_template = $template_files[1];
    }
 
    ob_start();
    include __DIR__ . '/' . $selected_template; 
    $html = ob_get_clean();
    
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    
    saveResumeToDatabase($conn, $user_id, $template_id);
    
    while (ob_get_level()) ob_end_clean();
    $filename = 'Resume_' . preg_replace('/\s+/', '_', ($header[0]['first_name'] ?? 'User')) . '_Template_' . $template_id . '.pdf';
    $dompdf->stream($filename, ["Attachment" => true]);
    exit;
}


function fetchTableData($conn, $table, $user_id) {
    if (!$conn) return [];
    $result = mysqli_query($conn, "SELECT * FROM $table WHERE user_id = '$user_id'");
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}


$header = $_SESSION['current_header'] ?? [];
$experience = $_SESSION['current_experience'] ?? [];
$education = $_SESSION['current_education'] ?? [];
$languages = $_SESSION['current_languages'] ?? [];
$skills = $_SESSION['current_skills'] ?? [];
$summary = $_SESSION['current_summary'] ?? [];

if (isset($_POST['finalize'])) {
    $finalData = [
        "header" => $header,
        "experience" => $experience,
        "education" => $education,
        "languages" => $languages,
        "skills" => $skills,
        "summary" => $summary
    ];
    $data_json = mysqli_real_escape_string($conn, json_encode($finalData));
    $check = mysqli_query($conn, "SELECT * FROM final_resume WHERE user_id='$user_id'");
    
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE final_resume SET data_json='$data_json', status='final', updated_at=NOW() WHERE user_id='$user_id'");
    } else {
        mysqli_query($conn, "INSERT INTO final_resume (user_id, data_json, status) VALUES ('$user_id', '$data_json', 'final')");
    }
    
    
    saveResumeToDatabase($conn, $user_id, $template_id);
    
    echo json_encode(["status" => "success"]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finalize | CVForge</title>
  <link rel="stylesheet" href="./styles/finalize.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="builder-container">


  <aside class="sidebar">
    <div class="brand">
      <h2>CVForge</h2>
      <div class="welcome-box">
        <p>👋 Welcome, <b>
          <?php 
            echo htmlspecialchars(
              trim(($header[0]['first_name'] ?? '') . ' ' . ($header[0]['last_name'] ?? '')) ?: 'User'
            ); 
          ?>
        </b></p>
        <hr class="divider">
      </div>
    </div>
   <ul class="steps">
    <li><a href="builder.php">1 Header</a></li>
    <li><a href="experience.php">2 Experience</a></li>
    <li><a href="education.php">3 Education</a></li>
    <li><a href="skills.php">4 Skills</a></li>
    <li><a href="language.php">5 Language</a></li>
    <li><a href="summary.php">5 Summary</a></li>
    <li class="active"><a href="finalize.php">6 Finalize</a></li>
   </ul>
    <button class="logout-btn" onclick="window.location.href='index.html'">Logout</button>
  </aside>

  <main class="builder-content">
    <h1>Review Your Complete Resume</h1>
    <p>Check all sections below. Once you're satisfied, finalize or download your CV.</p>

    <?php 

$session_header = $_SESSION['current_header'] ?? [];
?>

<?php if (!empty($session_header)): ?>
  <div class="overview-box" style="text-align:center;">
    <h2>👤 <?= htmlspecialchars($session_header[0]['first_name'] . ' ' . $session_header[0]['last_name']); ?></h2>
    <p>📧 <?= htmlspecialchars($session_header[0]['email']); ?> | 📞 <?= htmlspecialchars($session_header[0]['phone']); ?></p>
    <p>📍 <?= htmlspecialchars(trim($session_header[0]['city'] . ', ' . $session_header[0]['state'] . ' ' . $session_header[0]['zip'])); ?></p>
  </div>
<?php else: ?>
  <p style="text-align:center; color:red;">⚠️ No header information found. Please complete the Header section first.</p>
<?php endif; ?>

    
<div class="overview-box">
  <h3>🧾 Summary</h3>
  <ul>
    <?php 
    $session_summary = $_SESSION['current_summary'] ?? [];
    if (!empty($session_summary)): ?>
      <?php foreach ($session_summary as $s): ?>
        <li>
          <b><?= htmlspecialchars($s['headline']); ?></b> — <?= htmlspecialchars($s['about']); ?><br>
          🎯 <?= htmlspecialchars($s['objective']); ?><br>
          <strong>Experience:</strong> <?= htmlspecialchars($s['years_experience']); ?> years |
          <strong>Strengths:</strong> <?= htmlspecialchars($s['strengths']); ?> |
          <strong>Soft Skills:</strong> <?= htmlspecialchars($s['soft_skills']); ?><br>
          🏆 <?= htmlspecialchars($s['achievements']); ?> | 💻 <?= htmlspecialchars($s['projects']); ?><br>
          🔗 <a href="<?= htmlspecialchars($s['linkedin']); ?>" target="_blank">LinkedIn</a> | 
          <a href="<?= htmlspecialchars($s['portfolio']); ?>" target="_blank">Portfolio</a><br>
          🎯 <b>Hobbies:</b> <?= htmlspecialchars($s['hobbies']); ?>
        </li>
      <?php endforeach; ?>
    <?php else: ?>
      <li>No summary available.</li>
    <?php endif; ?>
  </ul>
</div>

   
<div class="overview-box">
  <h3>💼 Experience</h3>
  <?php 
  $session_experience = $_SESSION['current_experience'] ?? [];
  if (!empty($session_experience)): ?>
    <?php foreach ($session_experience as $exp): ?>
      <div class="item">
        <b><?= htmlspecialchars($exp['job_title']); ?></b> – <?= htmlspecialchars($exp['company']); ?><br>
        <small>📅 <?= htmlspecialchars($exp['start_date']); ?> – <?= htmlspecialchars($exp['end_date']); ?></small>
        <p><?= htmlspecialchars($exp['description']); ?></p>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No experience added yet.</p>
  <?php endif; ?>
</div>

    
<div class="overview-box">
  <h3>🎓 Education</h3>
  <?php 
  $session_education = $_SESSION['current_education'] ?? [];
  if (!empty($session_education)): ?>
    <?php foreach ($session_education as $edu): ?>
      <div class="item">
        <b><?= htmlspecialchars($edu['degree']); ?></b> – <?= htmlspecialchars($edu['school']); ?><br>
        <small>📅 <?= htmlspecialchars($edu['start_date']); ?> – <?= htmlspecialchars($edu['end_date']); ?></small>
        <?php if (!empty($edu['description'])): ?>
          <p><?= htmlspecialchars($edu['description']); ?></p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No education records available.</p>
  <?php endif; ?>
</div>

 
<div class="overview-box">
  <h3>⚡ Skills</h3>
  <?php 
  $session_skills = $_SESSION['current_skills'] ?? [];
  if (!empty($session_skills)): ?>
    <div class="tags">
      <?php foreach ($session_skills as $skill): ?>
        <span>⚡ <?= htmlspecialchars($skill['skill_name']); ?></span>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No skills added yet.</p>
  <?php endif; ?>
</div>

   
<div class="overview-box">
  <h3>🌍 Languages</h3>
  <?php 
  $session_languages = $_SESSION['current_languages'] ?? [];
  if (!empty($session_languages)): ?>
    <div class="tags">
      <?php foreach ($session_languages as $lang): ?>
        <span>💬 <?= htmlspecialchars($lang['language']); ?> (<?= htmlspecialchars($lang['proficiency']); ?>%)</span>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No languages added yet.</p>
  <?php endif; ?>
</div>
 
    <div class="button-row">
      <button class="btn-prev" onclick="window.location.href='summary.php'">← Back</button>
      <button class="btn-save" id="finalizeBtn">Finalize & Save</button>
      <button class="btn-download" id="downloadBtn">📄 Download PDF</button>
      <button class="btn-next" onclick="window.location.href='home.php'">HOME →</button>
    </div>
  </main>

 
<aside class="preview">
  <video autoplay muted loop id="previewBg">
    <source src="bg_section.mp4" type="video/mp4">
  </video>
  <div class="card">
    <h2><?= htmlspecialchars(trim(($session_header[0]['first_name'] ?? '') . ' ' . ($session_header[0]['last_name'] ?? '')) ?: 'Your Name'); ?></h2>
    <p><?= htmlspecialchars(($session_header[0]['city'] ?? '') . ', ' . ($session_header[0]['state'] ?? '')); ?></p>
    <hr>
    <p><?= htmlspecialchars($session_summary[0]['headline'] ?? 'Your headline will appear here.'); ?></p>
  </div>
</aside>

<script>
document.getElementById("finalizeBtn").addEventListener("click", async () => {

  const res = await fetch("finalize.php", { 
    method: "POST", 
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({ finalize: true }) 
  });
  const data = await res.json();

  if (data.status === "success") {
    Swal.fire({
      icon: "success",
      title: "Resume Finalized!",
      text: "Your data has been saved.",
      confirmButtonText: "Continue",
    }).then(() => window.location.href = "home.php");
  }
});

document.getElementById("downloadBtn").addEventListener("click", () => {
  window.location.href = "finalize.php?download=true";
});
</script>
</body>
</html>