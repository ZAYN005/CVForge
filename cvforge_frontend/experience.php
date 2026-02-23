<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();

if (empty($_SESSION['current_experience']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $servername = "127.0.0.1";
    $username = "root";
    $password = "1234";
    $dbname = "cvforge_db";
    
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if (!$conn->connect_error) {
            $sql = "SELECT job_title, company, start_date, end_date, description 
                    FROM experience 
                    WHERE user_id = ? 
                    ORDER BY start_date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $_SESSION['current_experience'] = [];
            
            while ($row = $result->fetch_assoc()) {
                $_SESSION['current_experience'][] = [
                    'job_title' => $row['job_title'],
                    'company' => $row['company'],
                    'start_date' => $row['start_date'],
                    'end_date' => $row['end_date'],
                    'description' => $row['description']
                ];
            }
            
            $stmt->close();
            $conn->close();
        }
    } catch (Exception $e) {
      
    }
}


if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = "Zayn Ali";
}
$userName = $_SESSION['user_name'];


$experiences = $_SESSION['current_experience'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Experience | CVForge</title>
  <link rel="stylesheet" href="./styles/experience.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<div class="builder-container">

  <aside class="sidebar">
    <div class="brand">
      <h2>CVForge</h2>
      <div class="welcome-box">
        <p>👋 Welcome, <b><?php echo htmlspecialchars($userName); ?></b></p>
        <hr class="divider">
      </div>
    </div>

    <ul class="steps">
      <li><a href="builder.php">1 Header</a></li>
      <li class="active"><a href="experience.php">2 Experience</a></li>
      <li><a href="education.php">3 Education</a></li>
      <li><a href="skills.php">4 Skills</a></li>
      <li><a href="language.php">5 Language</a></li>
      <li><a href="summary.php">6 Summary</a></li>
      <li><a href="finalize.php">7 Finalize</a></li>
    </ul>

    <button class="logout-btn" onclick="window.location.href='index.html'">Logout</button>
  </aside>

 
  <main class="builder-content">
    <h1>Tell us about your experience</h1>
    <p>List your most relevant jobs, internships, or projects that highlight your skills.</p>

    <form id="experienceForm" method="POST">
      <div class="form-row">
        <div class="form-group">
          <label>Job Title</label>
          <input type="text" id="jobTitle" name="job_title" placeholder="e.g., Software Engineer" required>
        </div>
        <div class="form-group">
          <label>Company Name</label>
          <input type="text" id="companyName" name="company" placeholder="e.g., Google" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Start Date</label>
          <input type="date" id="startDate" name="start_date">
        </div>
        <div class="form-group">
          <label>End Date</label>
          <input type="date" id="endDate" name="end_date">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group full">
          <label>Responsibilities / Achievements</label>
          <textarea id="description" name="description" rows="4" placeholder="Describe what you accomplished or worked on..." required></textarea>
        </div>
      </div>

      <div class="button-row">
        <button type="button" class="btn-prev">← Back</button>
        <button type="submit" class="btn-save">Save</button>
        <button type="button" id="addAnotherBtn">+ Add Another</button>
        <button type="button" class="btn-next">Next →</button>
      </div>
    </form>

    
    <?php if (!empty($experiences)): ?>
    <div class="existing-experiences" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
      <h3>Your Saved Experiences</h3>
      <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Manage your saved work experiences:</p>
      
      <div class="experiences-list">
        <?php foreach ($experiences as $index => $exp): ?>
          <div class="experience-item" style="background: white; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #6a4cff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
              <div style="flex: 1;">
                <strong style="font-size: 16px; color: #333; display: block;"><?= htmlspecialchars($exp['job_title']) ?></strong>
                <span style="color: #666; font-size: 14px;"><?= htmlspecialchars($exp['company']) ?></span>
                <?php if (!empty($exp['start_date']) || !empty($exp['end_date'])): ?>
                  <span style="color: #888; font-size: 13px; display: block; margin-top: 5px;">
                    <?= !empty($exp['start_date']) ? date('M Y', strtotime($exp['start_date'])) : '' ?>
                    <?= !empty($exp['end_date']) ? ' - ' . date('M Y', strtotime($exp['end_date'])) : (empty($exp['end_date']) && !empty($exp['start_date']) ? ' - Present' : '') ?>
                  </span>
                <?php endif; ?>
              </div>
              <button type="button" class="btn-delete" onclick="deleteExperience(<?= $index ?>)" style="background: #ff4757; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s; margin-left: 15px;">Delete</button>
            </div>
            <?php if (!empty($exp['description'])): ?>
              <div style="color: #555; font-size: 14px; line-height: 1.4; margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee;">
                <?= htmlspecialchars($exp['description']) ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="margin-top: 15px; text-align: center;">
        <button type="button" onclick="deleteAllExperiences()" style="background: #ff6b6b; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s;">Delete All Experiences</button>
      </div>
    </div>
    <?php endif; ?>
  </main>

 
  <aside class="preview">
    <video autoplay muted loop id="previewBg">
      <source src="bg_section.mp4" type="video/mp4">
    </video>
    <div class="card">
      <h2>Experience Preview</h2>
      <div id="experiencePreview">
        <?php if (!empty($experiences)): ?>
          <?php foreach ($experiences as $exp): ?>
            <div class="preview-item" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
              <h3 style="margin: 0 0 5px 0; color: #333; font-size: 18px;"><?= htmlspecialchars($exp['job_title']) ?></h3>
              <p style="margin: 0 0 8px 0; color: #666; font-size: 14px;">
                <?= htmlspecialchars($exp['company']) ?>
                <?php if (!empty($exp['start_date']) || !empty($exp['end_date'])): ?>
                  | <?= !empty($exp['start_date']) ? date('M Y', strtotime($exp['start_date'])) : '' ?>
                  <?= !empty($exp['end_date']) ? ' - ' . date('M Y', strtotime($exp['end_date'])) : (empty($exp['end_date']) && !empty($exp['start_date']) ? ' - Present' : '') ?>
                <?php endif; ?>
              </p>
              <?php if (!empty($exp['description'])): ?>
                <p style="margin: 0; color: #555; font-size: 14px; line-height: 1.4;"><?= htmlspecialchars($exp['description']) ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color: #666; font-style: italic;">No experience entries added yet</p>
        <?php endif; ?>
      </div>
    </div>
  </aside>
</div>

<script>
const form = document.getElementById("experienceForm");
const addBtn = document.getElementById("addAnotherBtn");
const job = document.getElementById("jobTitle");
const company = document.getElementById("companyName");
const start = document.getElementById("startDate");
const end = document.getElementById("endDate");
const desc = document.getElementById("description");
const preview = document.getElementById("experiencePreview");


function updatePreview() {
  const jobVal = job.value.trim();
  const compVal = company.value.trim();
  const startVal = start.value ? new Date(start.value) : null;
  const endVal = end.value ? new Date(end.value) : null;
  const descVal = desc.value.trim();

  let duration = '';
  if (startVal) {
    duration = startVal.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    if (endVal) {
      duration += ' - ' + endVal.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    } else if (startVal) {
      duration += ' - Present';
    }
  }

  if (jobVal || compVal || descVal) {
    preview.innerHTML = `
      <div class="preview-item" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
        <h3 style="margin: 0 0 5px 0; color: #333; font-size: 18px;">${jobVal || 'Job Title'}</h3>
        <p style="margin: 0 0 8px 0; color: #666; font-size: 14px;">
          ${compVal || 'Company'}${duration ? " | " + duration : ""}
        </p>
        ${descVal ? `<p style="margin: 0; color: #555; font-size: 14px; line-height: 1.4;">${descVal}</p>` : ''}
      </div>
    `;
  } else {
    preview.innerHTML = '<p style="color: #666; font-style: italic;">No experience entries added yet</p>';
  }
}

[job, company, start, end, desc].forEach(el => el.addEventListener("input", updatePreview));


form.addEventListener("submit", async (e) => {
  e.preventDefault();
  
  const formData = new FormData(form);
  const jobVal = job.value.trim();
  const companyVal = company.value.trim();
  const descVal = desc.value.trim();

  if (!jobVal || !companyVal || !descVal) {
    Swal.fire({
      icon: "warning",
      title: "Missing Information",
      text: "Please fill all required fields.",
      confirmButtonColor: "#6a4cff"
    });
    return;
  }

  try {
    const res = await fetch("save_experience.php", { method: "POST", body: formData });
    const data = await res.json();

    if (data.status === "success") {
      Swal.fire({
        toast: true,
        icon: "success",
        title: data.message,
        position: "bottom-end",
        showConfirmButton: false,
        timer: 2000,
        background: "#333",
        color: "#fff"
      });
      
    
      setTimeout(() => {
        window.location.reload();
      }, 500);
    } else {
      Swal.fire({
        icon: "warning",
        title: "Something went wrong",
        text: data.message,
        confirmButtonColor: "#6a4cff"
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error saving experience",
      text: "Please check your connection.",
      confirmButtonColor: "#6a4cff"
    });
  }
});


addBtn.addEventListener("click", async (e) => {
  e.preventDefault();
  
  const jobVal = job.value.trim();
  const companyVal = company.value.trim();
  const descVal = desc.value.trim();
  
  if (!jobVal || !companyVal || !descVal) {
    Swal.fire({
      icon: "warning",
      title: "Missing Information",
      text: "Please fill all required fields before adding another entry.",
      confirmButtonColor: "#6a4cff"
    });
    return;
  }
  
  const formData = new FormData();
  formData.append('job_title', jobVal);
  formData.append('company', companyVal);
  formData.append('start_date', start.value);
  formData.append('end_date', end.value);
  formData.append('description', descVal);
  
  try {
    const res = await fetch("save_experience.php", { 
      method: "POST", 
      body: formData 
    });
    const data = await res.json();

    if (data.status === "success") {
      job.value = '';
      company.value = '';
      start.value = '';
      end.value = '';
      desc.value = '';
      updatePreview();
      
      Swal.fire({
        toast: true,
        icon: "success",
        title: "Experience saved!",
        text: "Form cleared for new entry",
        position: "bottom-end",
        showConfirmButton: false,
        timer: 2000,
        background: "#333",
        color: "#fff"
      });
      
      
      setTimeout(() => {
        window.location.reload();
      }, 500);
      
    } else {
      Swal.fire({
        icon: "error",
        title: "Save failed",
        text: data.message,
        confirmButtonColor: "#6a4cff"
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Connection Error",
      text: "Please check your internet connection.",
      confirmButtonColor: "#6a4cff"
    });
  }
});


async function deleteExperience(index) {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "This experience entry will be removed from your profile",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_experience.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `index=${index}`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "Experience entry deleted successfully!", 
          position: "bottom-end", 
          timer: 2000, 
          showConfirmButton: false,
          background: "#333",
          color: "#fff"
        });
        
        setTimeout(() => {
          window.location.reload();
        }, 500);
      } else {
        Swal.fire({ icon: "error", title: "Error", text: data.message });
      }
    } catch {
      Swal.fire({ icon: "error", title: "Connection Error", text: "Check your server connection." });
    }
  }
}


async function deleteAllExperiences() {
  const result = await Swal.fire({
    title: 'Delete all experience entries?',
    text: "This will remove all your saved experience entries permanently",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete all!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_experience.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `delete_all=true`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "All experience entries deleted successfully!", 
          position: "bottom-end", 
          timer: 2000, 
          showConfirmButton: false,
          background: "#333",
          color: "#fff"
        });
        
        setTimeout(() => {
          window.location.reload();
        }, 500);
      } else {
        Swal.fire({ icon: "error", title: "Error", text: data.message });
      }
    } catch {
      Swal.fire({ icon: "error", title: "Connection Error", text: "Check your server connection." });
    }
  }
}


document.querySelector(".btn-prev").addEventListener("click", () => {
  window.location.href = "builder.php";
});
document.querySelector(".btn-next").addEventListener("click", () => {
  window.location.href = "education.php";
});


updatePreview();
</script>
</body>
</html>