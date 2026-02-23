<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = "Zayn Ali";
}
$userName = $_SESSION['user_name'];


$education = $_SESSION['current_education'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Education | CVForge</title>
  <link rel="stylesheet" href="./styles/education.css" />
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
      <li><a href="experience.php">2 Experience</a></li>
      <li class="active"><a href="education.php">3 Education</a></li>
      <li><a href="skills.php">4 Skills</a></li>
      <li><a href="language.php">5 Language</a></li>
      <li><a href="summary.php">6 Summary</a></li>
      <li><a href="finalize.php">7 Finalize</a></li>
    </ul>

    <button class="logout-btn" onclick="window.location.href='index.html'">Logout</button>
  </aside>

  
  <main class="builder-content">
    <h1>Tell us about your education</h1>
    <p>List your degrees, certifications, or relevant coursework that support your career goals.</p>

    <form id="educationForm" method="POST">
      <div class="form-row">
        <div class="form-group">
          <label>Degree / Qualification</label>
          <input type="text" id="degree" name="degree" placeholder="e.g., Bachelor of Engineering" required>
        </div>
        <div class="form-group">
          <label>Institution Name</label>
          <input type="text" id="school" name="school" placeholder="e.g., Stanford University" required>
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
          <label>Description / Highlights</label>
          <textarea id="description" name="description" rows="4" placeholder="Describe your academic achievements or research work..." required></textarea>
        </div>
      </div>

      <div class="button-row">
        <button type="button" class="btn-prev">← Back</button>
        <button type="submit" class="btn-save">Save</button>
        <button type="button" id="addAnotherBtn">+ Add Another</button>
        <button type="button" class="btn-next">Next →</button>
      </div>
    </form>

    
    <?php if (!empty($education)): ?>
    <div class="existing-education" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
      <h3>Your Saved Education</h3>
      <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Manage your saved education entries:</p>
      <div class="education-list">
        <?php foreach ($education as $index => $edu): ?>
          <div class="education-item" style="background: white; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #6a4cff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
              <div style="flex: 1;">
                <strong style="font-size: 16px; color: #333; display: block;"><?= htmlspecialchars($edu['degree']) ?></strong>
                <span style="color: #666; font-size: 14px;"><?= htmlspecialchars($edu['school']) ?></span>
                <?php if (!empty($edu['start_date']) || !empty($edu['end_date'])): ?>
                  <span style="color: #888; font-size: 13px; display: block; margin-top: 5px;">
                    <?= !empty($edu['start_date']) ? date('Y', strtotime($edu['start_date'])) : '' ?>
                    <?= !empty($edu['end_date']) ? ' - ' . date('Y', strtotime($edu['end_date'])) : '' ?>
                  </span>
                <?php endif; ?>
              </div>
              <button type="button" class="btn-delete" onclick="deleteEducation(<?= $index ?>)" style="background: #ff4757; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s; margin-left: 15px;">Delete</button>
            </div>
            <?php if (!empty($edu['description'])): ?>
              <div style="color: #555; font-size: 14px; line-height: 1.4; margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee;">
                <?= htmlspecialchars($edu['description']) ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="margin-top: 15px; text-align: center;">
        <button type="button" onclick="deleteAllEducation()" style="background: #ff6b6b; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s;">Delete All Education</button>
      </div>
    </div>
    <?php endif; ?>
  </main>

  
  <aside class="preview">
    <video autoplay muted loop id="previewBg">
      <source src="bg_section.mp4" type="video/mp4">
    </video>
    <div class="card">
      <h2>Education Preview</h2>
      <div id="educationPreview">
        <?php if (!empty($education)): ?>
          <?php foreach ($education as $edu): ?>
            <div class="preview-item" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
              <h3 style="margin: 0 0 5px 0; color: #333; font-size: 18px;"><?= htmlspecialchars($edu['degree'] ?? '') ?></h3>
              <p style="margin: 0 0 8px 0; color: #666; font-size: 14px;">
                <?= htmlspecialchars($edu['school'] ?? '') ?>
                <?php if (!empty($edu['start_date']) || !empty($edu['end_date'])): ?>
                  | <?= !empty($edu['start_date']) ? date('M Y', strtotime($edu['start_date'])) : '' ?>
                  <?= !empty($edu['end_date']) ? ' - ' . date('M Y', strtotime($edu['end_date'])) : (empty($edu['end_date']) && !empty($edu['start_date']) ? ' - Present' : '') ?>
                <?php endif; ?>
              </p>
              <?php if (!empty($edu['description'])): ?>
                <p style="margin: 0; color: #555; font-size: 14px; line-height: 1.4;"><?= htmlspecialchars($edu['description'] ?? '') ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color: #666; font-style: italic;">No education entries added yet</p>
        <?php endif; ?>
      </div>
    </div>
  </aside>
</div>

<script>
const form = document.getElementById("educationForm");
const addBtn = document.getElementById("addAnotherBtn");
const degree = document.getElementById("degree");
const school = document.getElementById("school");
const start = document.getElementById("startDate");
const end = document.getElementById("endDate");
const desc = document.getElementById("description");
const preview = document.getElementById("educationPreview");


function updatePreview() {
  const degreeVal = degree.value.trim();
  const schoolVal = school.value.trim();
  const startVal = start.value ? new Date(start.value).getFullYear() : "";
  const endVal = end.value ? new Date(end.value).getFullYear() : "";
  const duration = startVal && endVal ? `${startVal} - ${endVal}` : (startVal || endVal);
  const descVal = desc.value.trim();

  if (degreeVal || schoolVal || descVal) {
    preview.innerHTML = `
      <div class="preview-item" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
        <h3 style="margin: 0 0 5px 0; color: #333; font-size: 18px;">${degreeVal || 'Degree Title'}</h3>
        <p style="margin: 0 0 8px 0; color: #666; font-size: 14px;">
          ${schoolVal || 'University'}${duration ? " | " + duration : ""}
        </p>
        ${descVal ? `<p style="margin: 0; color: #555; font-size: 14px; line-height: 1.4;">${descVal}</p>` : ''}
      </div>
    `;
  } else {
    preview.innerHTML = '<p style="color: #666; font-style: italic;">No education entries added yet</p>';
  }
}

[degree, school, start, end, desc].forEach(el => el.addEventListener("input", updatePreview));


form.addEventListener("submit", async (e) => {
  e.preventDefault();
  
  const formData = new FormData(form);
  const degreeVal = degree.value.trim();
  const schoolVal = school.value.trim();
  const descVal = desc.value.trim();

  if (!degreeVal || !schoolVal || !descVal) {
    Swal.fire({
      icon: "warning",
      title: "Missing Information",
      text: "Please fill all required fields.",
      confirmButtonColor: "#6a4cff"
    });
    return;
  }

  try {
    const res = await fetch("save_education.php", { method: "POST", body: formData });
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
      title: "Error saving education",
      text: "Please check your connection.",
      confirmButtonColor: "#6a4cff"
    });
  }
});


addBtn.addEventListener("click", async (e) => {
  e.preventDefault();
  
  const degreeVal = degree.value.trim();
  const schoolVal = school.value.trim();
  const descVal = desc.value.trim();
  
  if (!degreeVal || !schoolVal || !descVal) {
    Swal.fire({
      icon: "warning",
      title: "Missing Information",
      text: "Please fill all required fields before adding another entry.",
      confirmButtonColor: "#6a4cff"
    });
    return;
  }
  
  const formData = new FormData();
  formData.append('degree', degreeVal);
  formData.append('school', schoolVal);
  formData.append('start_date', start.value);
  formData.append('end_date', end.value);
  formData.append('description', descVal);
  
  try {
    const res = await fetch("save_education.php", { 
      method: "POST", 
      body: formData 
    });
    const data = await res.json();

    if (data.status === "success") {
      degree.value = '';
      school.value = '';
      start.value = '';
      end.value = '';
      desc.value = '';
      updatePreview();
      
      Swal.fire({
        toast: true,
        icon: "success",
        title: "Education saved!",
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


async function deleteEducation(index) {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "This education entry will be removed from your profile",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_education.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `index=${index}`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "Education entry deleted successfully!", 
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


async function deleteAllEducation() {
  const result = await Swal.fire({
    title: 'Delete all education entries?',
    text: "This will remove all your saved education entries permanently",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete all!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_education.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `delete_all=true`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "All education entries deleted successfully!", 
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
  window.location.href = "experience.php";
});
document.querySelector(".btn-next").addEventListener("click", () => {
  window.location.href = "skills.php";
});


updatePreview();
</script>
</body>
</html>