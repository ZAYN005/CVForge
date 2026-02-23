<?php
session_start();
$userName = $_SESSION['user_name'] ?? "Zayn Ali";

$skills = $_SESSION['current_skills'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Skills | CVForge</title>
  <link rel="stylesheet" href="./styles/skills.css" />
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
      <li><a href="education.php">3 Education</a></li>
      <li class="active"><a href="skills.php">4 Skills</a></li>
      <li><a href="language.php">5 Language</a></li>
      <li><a href="summary.php">6 Summary</a></li>
      <li><a href="finalize.php">7 Finalize</a></li>
    </ul>
    <button class="logout-btn" onclick="window.location.href='index.html'">Logout</button>
  </aside>

  
  <main class="builder-content">
    <h1>Highlight your skills</h1>
    <p>Showcase your key abilities, experience, and confidence level.</p>

    <form id="skillsForm" method="POST">
      <div class="form-row">
        <div class="form-group">
          <label>Skill Name</label>
          <input type="text" id="skillName" name="skill_name" placeholder="e.g., Python, Leadership" required>
        </div>
        <div class="form-group">
          <label>Proficiency</label>
          <select id="proficiency" name="proficiency" required>
            <option value="">Select Level</option>
            <option>Beginner</option>
            <option>Intermediate</option>
            <option>Expert</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Years of Experience</label>
          <input type="number" id="years" name="years_experience" min="0" max="50" placeholder="e.g., 3">
        </div>
        <div class="form-group">
          <label>Rating (1–5)</label>
          <input type="range" id="rating" name="rating" min="1" max="5" value="3" oninput="updateRatingLabel(this.value)">
          <span id="ratingValue">3</span>
        </div>
      </div>

      <div class="button-row">
        <button type="button" class="btn-prev">← Back</button>
        <button type="button" class="btn-save" id="saveBtn">Save</button>
        <button type="button" id="addSkillBtn">+ Add Another</button>
        <button type="button" class="btn-next">Next →</button>
      </div>
    </form>

  
    <?php if (!empty($skills)): ?>
    <div class="existing-skills" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
      <h3>Your Saved Skills</h3>
      <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Manage your saved skills:</p>
      <div class="skills-list">
        <?php foreach ($skills as $index => $skill): ?>
          <div class="skill-item" style="background: white; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #6a4cff; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="flex: 1;">
              <strong style="font-size: 16px; color: #333;"><?= htmlspecialchars($skill['skill_name']) ?></strong> - 
              <span style="color: #666;"><?= htmlspecialchars($skill['proficiency']) ?> </span>
              <span style="color: #888;">(<?= $skill['years_experience'] ? $skill['years_experience'] . ' yrs' : '0 yrs' ?>)</span>
              - <span style="color: #ffa500;">⭐<?= $skill['rating'] ?>/5</span>
            </div>
            <button type="button" class="btn-delete" onclick="deleteSkill(<?= $index ?>)" style="background: #ff4757; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s;">Delete</button>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="margin-top: 15px; text-align: center;">
        <button type="button" onclick="deleteAllSkills()" style="background: #ff6b6b; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s;">Delete All Skills</button>
      </div>
    </div>
    <?php endif; ?>
  </main>

  <aside class="preview">
    <video autoplay muted loop id="previewBg">
      <source src="bg_section.mp4" type="video/mp4">
    </video>
    <div class="card">
      <h2>Skills Preview</h2>
      <ul id="skillsList">
        <?php if (!empty($skills)): ?>
          <?php foreach ($skills as $skill): ?>
            <li>
              <b><?= htmlspecialchars($skill['skill_name']) ?></b> — 
              <?= htmlspecialchars($skill['proficiency']) ?> 
              (<?= $skill['years_experience'] ? $skill['years_experience'] . ' yrs' : '0 yrs' ?>)
              <span class="stars"><?= str_repeat('★', $skill['rating']) . str_repeat('☆', 5 - $skill['rating']) ?></span>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No skills added yet</li>
        <?php endif; ?>
      </ul>
    </div>
  </aside>
</div>

<script>
function updateRatingLabel(value) {
  document.getElementById('ratingValue').textContent = value;
}

const form = document.getElementById('skillsForm');
const list = document.getElementById('skillsList');
const addSkillBtn = document.getElementById('addSkillBtn');
const saveBtn = document.getElementById('saveBtn');


async function saveSkill(showToastText, clearForm = false) {
  const formData = new FormData(form);
  const name = document.getElementById('skillName').value.trim();
  const proficiency = document.getElementById('proficiency').value.trim();

  if (!name || !proficiency) {
    Swal.fire({
      icon: "warning",
      title: "Missing Fields",
      text: "Please fill Skill Name and Proficiency!",
      confirmButtonColor: "#b784ff"
    });
    return false;
  }

  try {
    const res = await fetch("save_skills.php", { method: "POST", body: formData });
    const rawText = await res.text();
    
    try {
      const data = JSON.parse(rawText);
      
      if (data.status === "success") {
        Swal.fire({
          toast: true,
          icon: "success",
          title: showToastText,
          position: "bottom-end",
          showConfirmButton: false,
          timer: 2000,
          background: "#333",
          color: "#fff"
        });

        if (clearForm) {
        
          form.reset();
          updateRatingLabel(3);
        }
        
      
        setTimeout(() => {
          window.location.reload();
        }, 500);
        
        return true;
      } else {
        Swal.fire({ 
          icon: "error", 
          title: "Error", 
          text: data.message 
        });
        return false;
      }
    } catch (jsonError) {
     
      Swal.fire({
        toast: true,
        icon: "success", 
        title: showToastText,
        position: "bottom-end",
        showConfirmButton: false,
        timer: 2000,
        background: "#333",
        color: "#fff"
      });
      
      if (clearForm) {
        form.reset();
        updateRatingLabel(3);
      }
      return true;
    }
  } catch (err) {
    Swal.fire({
      icon: "error",
      title: "Connection Error",
      text: "Please check your server or database connection.",
      confirmButtonColor: "#b784ff"
    });
    return false;
  }
}


async function deleteSkill(index) {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "This skill will be removed from your profile",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_skill.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `index=${index}`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "Skill deleted successfully!", 
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


async function deleteAllSkills() {
  const result = await Swal.fire({
    title: 'Delete all skills?',
    text: "This will remove all your saved skills permanently",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete all!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_skill.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `delete_all=true`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "All skills deleted successfully!", 
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


addSkillBtn.addEventListener('click', async (e) => {
  e.preventDefault();
  await saveSkill("Skill added successfully!", true);
});


saveBtn.addEventListener('click', async (e) => {
  e.preventDefault();
  await saveSkill("Skill saved successfully!", false);
});


document.querySelector(".btn-next").addEventListener("click", () => {
  window.location.href = "language.php";
});
document.querySelector(".btn-prev").addEventListener("click", () => {
  window.location.href = "education.php";
});
</script>
</body>
</html>