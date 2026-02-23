<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$resume_id = $_GET['id'] ?? 0;


$resume_data = [];
if ($resume_id) {
    $stmt = $conn->prepare("SELECT * FROM resume_versions WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $resume_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resume_data = $result->fetch_assoc();
}

$stmt->close();
$conn->close();

if (empty($resume_data)) {
    header("Location: dashboard.php");
    exit;
}


$header = json_decode($resume_data['header_data'], true) ?? [];
$experience = json_decode($resume_data['experience_data'], true) ?? [];
$education = json_decode($resume_data['education_data'], true) ?? [];
$skills = json_decode($resume_data['skills_data'], true) ?? [];
$languages = json_decode($resume_data['languages_data'], true) ?? [];
$summary = json_decode($resume_data['summary_data'], true) ?? [];


$header_data = $header[0] ?? [];
$summary_data = $summary[0] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Resume | CVForge</title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .editor-container {
      display: grid;
      grid-template-columns: 1fr 400px;
      gap: 30px;
      max-width: 1400px;
      margin: 80px auto 40px;
      padding: 20px;
    }
    
    .editor-form {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .form-section {
      margin-bottom: 40px;
      padding-bottom: 30px;
      border-bottom: 2px solid #f0f0f0;
    }
    
    .form-section h3 {
      color: #b784ff;
      margin-bottom: 20px;
      font-size: 1.4rem;
    }
    
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #333;
    }
    
    .form-group input, 
    .form-group textarea {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e1e1e1;
      border-radius: 8px;
      font-size: 15px;
      transition: all 0.3s ease;
    }
    
    .form-group input:focus, 
    .form-group textarea:focus {
      border-color: #b784ff;
      box-shadow: 0 0 0 3px rgba(183, 132, 255, 0.1);
    }
    
    .item-row {
      background: #f8f9ff;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 15px;
      border-left: 4px solid #b784ff;
    }
    
    .add-btn {
      background: #b784ff;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      margin-top: 10px;
    }
    
    .preview-panel {
      position: sticky;
      top: 20px;
      height: fit-content;
    }
    
    .action-buttons {
      display: flex;
      gap: 15px;
      margin-top: 30px;
      justify-content: center;
      flex-wrap: wrap;
    }
    
    .btn-save, .btn-download, .btn-back {
      padding: 15px 30px;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      font-size: 16px;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }
    
    .btn-save {
      background: #b784ff;
      color: white;
    }
    
    .btn-download {
      background: #28a745;
      color: white;
    }
    
    .btn-back {
      background: #6c757d;
      color: white;
    }
    
    .btn-save:hover, .btn-download:hover, .btn-back:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .editor-header {
      text-align: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #f0f0f0;
    }

    .editor-header h1 {
      color: #333;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <header class="navbar" style="position: fixed; top: 0; width: 100%; z-index: 1000; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div class="logo"><span>CV</span>Forge</div>
    <nav class="nav-links">
      <a href="dashboard.php">Dashboard</a>
      <a href="#form">Edit Resume</a>
      <a href="#preview">Preview</a>
    </nav>
    <div class="user-info">
      <span>👋 Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
      <a href="http://localhost/cvforge_api/logout.php" class="logout-btn">Logout</a>
    </div>
  </header>

  <div class="editor-container">
    
   
    <div class="editor-form">
      <div class="editor-header">
        <h1>✏️ Edit Your Resume</h1>
        <p>Update your information and download the new version</p>
      </div>
      
      <form id="resumeForm">
        <input type="hidden" name="resume_id" value="<?= $resume_id ?>">
        
     
        <div class="form-section">
          <h3>👤 Personal Information</h3>
          <div class="form-grid">
            <div class="form-group">
              <label>First Name *</label>
              <input type="text" name="first_name" value="<?= htmlspecialchars($header_data['first_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
              <label>Last Name *</label>
              <input type="text" name="last_name" value="<?= htmlspecialchars($header_data['last_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
              <label>Email *</label>
              <input type="email" name="email" value="<?= htmlspecialchars($header_data['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
              <label>Phone</label>
              <input type="text" name="phone" value="<?= htmlspecialchars($header_data['phone'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>City</label>
              <input type="text" name="city" value="<?= htmlspecialchars($header_data['city'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label>State</label>
              <input type="text" name="state" value="<?= htmlspecialchars($header_data['state'] ?? '') ?>">
            </div>
          </div>
        </div>

       
        <div class="form-section">
          <h3>📝 Professional Summary</h3>
          <div class="form-group">
            <label>Headline</label>
            <input type="text" name="headline" value="<?= htmlspecialchars($summary_data['headline'] ?? '') ?>" placeholder="e.g., Senior Software Engineer">
          </div>
          <div class="form-group">
            <label>About You</label>
            <textarea name="about" rows="4" placeholder="Describe your professional background..."><?= htmlspecialchars($summary_data['about'] ?? '') ?></textarea>
          </div>
        </div>

       
        <div class="form-section">
          <h3>💼 Work Experience</h3>
          <div id="experience-container">
            <?php foreach ($experience as $index => $exp): ?>
            <div class="item-row" data-index="<?= $index ?>">
              <div class="form-grid">
                <div class="form-group">
                  <label>Job Title</label>
                  <input type="text" name="experience[<?= $index ?>][job_title]" value="<?= htmlspecialchars($exp['job_title'] ?? '') ?>">
                </div>
                <div class="form-group">
                  <label>Company</label>
                  <input type="text" name="experience[<?= $index ?>][company]" value="<?= htmlspecialchars($exp['company'] ?? '') ?>">
                </div>
                <div class="form-group">
                  <label>Start Date</label>
                  <input type="text" name="experience[<?= $index ?>][start_date]" value="<?= htmlspecialchars($exp['start_date'] ?? '') ?>" placeholder="YYYY-MM-DD">
                </div>
                <div class="form-group">
                  <label>End Date</label>
                  <input type="text" name="experience[<?= $index ?>][end_date]" value="<?= htmlspecialchars($exp['end_date'] ?? '') ?>" placeholder="YYYY-MM-DD or Present">
                </div>
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea name="experience[<?= $index ?>][description]" rows="3"><?= htmlspecialchars($exp['description'] ?? '') ?></textarea>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="add-btn" onclick="addExperience()">+ Add Experience</button>
        </div>

      
        <div class="form-section">
          <h3>🎓 Education</h3>
          <div id="education-container">
            <?php foreach ($education as $index => $edu): ?>
            <div class="item-row" data-index="<?= $index ?>">
              <div class="form-grid">
                <div class="form-group">
                  <label>Degree</label>
                  <input type="text" name="education[<?= $index ?>][degree]" value="<?= htmlspecialchars($edu['degree'] ?? '') ?>">
                </div>
                <div class="form-group">
                  <label>Institution</label>
                  <input type="text" name="education[<?= $index ?>][school]" value="<?= htmlspecialchars($edu['school'] ?? '') ?>">
                </div>
                <div class="form-group">
                  <label>Start Date</label>
                  <input type="text" name="education[<?= $index ?>][start_date]" value="<?= htmlspecialchars($edu['start_date'] ?? '') ?>" placeholder="YYYY-MM-DD">
                </div>
                <div class="form-group">
                  <label>End Date</label>
                  <input type="text" name="education[<?= $index ?>][end_date]" value="<?= htmlspecialchars($edu['end_date'] ?? '') ?>" placeholder="YYYY-MM-DD">
                </div>
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea name="education[<?= $index ?>][description]" rows="3"><?= htmlspecialchars($edu['description'] ?? '') ?></textarea>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="add-btn" onclick="addEducation()">+ Add Education</button>
        </div>

        
        <div class="form-section">
          <h3>⚡ Skills</h3>
          <div id="skills-container">
            <?php foreach ($skills as $index => $skill): ?>
            <div class="item-row" data-index="<?= $index ?>">
              <div class="form-group">
                <label>Skill Name</label>
                <input type="text" name="skills[<?= $index ?>][skill_name]" value="<?= htmlspecialchars($skill['skill_name'] ?? '') ?>">
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="add-btn" onclick="addSkill()">+ Add Skill</button>
        </div>

       
        <div class="action-buttons">
          <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
          <button type="button" class="btn-save" onclick="saveResume()">💾 Save Changes</button>
          <button type="button" class="btn-download" onclick="downloadResume()">📄 Download PDF</button>
        </div>
      </form>
    </div>


    <div class="preview-panel" id="preview">
      <div class="card" style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
        <h2 style="color: #333; margin-bottom: 10px;"><?= htmlspecialchars(($header_data['first_name'] ?? '') . ' ' . ($header_data['last_name'] ?? '')) ?></h2>
        <p style="color: #666; margin-bottom: 5px;"><?= htmlspecialchars(($header_data['city'] ?? '') . ', ' . ($header_data['state'] ?? '')) ?></p>
        <p style="color: #666; margin-bottom: 15px;">📧 <?= htmlspecialchars($header_data['email'] ?? '') ?> | 📞 <?= htmlspecialchars($header_data['phone'] ?? '') ?></p>
        <hr style="margin: 20px 0; border: 1px solid #e1e1e1;">
        
        <?php if (!empty($summary_data['headline'])): ?>
          <h3 style="color: #b784ff; margin-bottom: 10px;"><?= htmlspecialchars($summary_data['headline']) ?></h3>
        <?php endif; ?>
        
        <?php if (!empty($summary_data['about'])): ?>
          <p style="color: #666; line-height: 1.6; margin-bottom: 20px;"><?= htmlspecialchars($summary_data['about']) ?></p>
        <?php endif; ?>
        
        <?php if (!empty($experience)): ?>
          <h4 style="color: #333; margin-bottom: 15px; border-bottom: 2px solid #b784ff; padding-bottom: 5px;">Experience</h4>
          <?php foreach ($experience as $exp): ?>
            <div style="margin-bottom: 15px;">
              <p style="margin: 5px 0; color: #333;"><strong><?= htmlspecialchars($exp['job_title'] ?? '') ?></strong> at <?= htmlspecialchars($exp['company'] ?? '') ?></p>
              <?php if (!empty($exp['description'])): ?>
                <p style="margin: 5px 0; color: #666; font-size: 0.9em;"><?= htmlspecialchars($exp['description']) ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
  let expCount = <?= count($experience) ?>;
  let eduCount = <?= count($education) ?>;
  let skillCount = <?= count($skills) ?>;

  function addExperience() {
    const container = document.getElementById('experience-container');
    const newRow = document.createElement('div');
    newRow.className = 'item-row';
    newRow.innerHTML = `
      <div class="form-grid">
        <div class="form-group">
          <label>Job Title</label>
          <input type="text" name="experience[${expCount}][job_title]" placeholder="Software Engineer">
        </div>
        <div class="form-group">
          <label>Company</label>
          <input type="text" name="experience[${expCount}][company]" placeholder="Company Name">
        </div>
        <div class="form-group">
          <label>Start Date</label>
          <input type="text" name="experience[${expCount}][start_date]" placeholder="YYYY-MM-DD">
        </div>
        <div class="form-group">
          <label>End Date</label>
          <input type="text" name="experience[${expCount}][end_date]" placeholder="YYYY-MM-DD or Present">
        </div>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="experience[${expCount}][description]" rows="3" placeholder="Describe your responsibilities..."></textarea>
      </div>
    `;
    container.appendChild(newRow);
    expCount++;
  }

  function addEducation() {
    const container = document.getElementById('education-container');
    const newRow = document.createElement('div');
    newRow.className = 'item-row';
    newRow.innerHTML = `
      <div class="form-grid">
        <div class="form-group">
          <label>Degree</label>
          <input type="text" name="education[${eduCount}][degree]" placeholder="Bachelor of Science">
        </div>
        <div class="form-group">
          <label>Institution</label>
          <input type="text" name="education[${eduCount}][school]" placeholder="University Name">
        </div>
        <div class="form-group">
          <label>Start Date</label>
          <input type="text" name="education[${eduCount}][start_date]" placeholder="YYYY-MM-DD">
        </div>
        <div class="form-group">
          <label>End Date</label>
          <input type="text" name="education[${eduCount}][end_date]" placeholder="YYYY-MM-DD">
        </div>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="education[${eduCount}][description]" rows="3" placeholder="Academic achievements..."></textarea>
      </div>
    `;
    container.appendChild(newRow);
    eduCount++;
  }

  function addSkill() {
    const container = document.getElementById('skills-container');
    const newRow = document.createElement('div');
    newRow.className = 'item-row';
    newRow.innerHTML = `
      <div class="form-group">
        <label>Skill Name</label>
        <input type="text" name="skills[${skillCount}][skill_name]" placeholder="JavaScript, Python, etc.">
      </div>
    `;
    container.appendChild(newRow);
    skillCount++;
  }

  async function saveResume() {
    const formData = new FormData(document.getElementById('resumeForm'));
    
    try {
      const response = await fetch('update_resume.php', {
        method: 'POST',
        body: formData
      });
      
      const result = await response.json();
      
      if (result.success) {
        Swal.fire({
          title: 'Success!',
          text: 'Resume updated successfully!',
          icon: 'success',
          confirmButtonText: 'OK',
          confirmButtonColor: '#b784ff'
        });
      } else {
        Swal.fire('Error', result.message, 'error');
      }
    } catch (error) {
      Swal.fire('Error', 'Failed to save resume', 'error');
    }
  }

  function downloadResume() {
    
    window.location.href = `download_updated.php?id=<?= $resume_id ?>`;
  }
  </script>
</body>
</html>