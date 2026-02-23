<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();


if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = "Zayn Ali";
}
$userName = $_SESSION['user_name'];


$summary = $_SESSION['current_summary'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Summary | CVForge</title>
  <link rel="stylesheet" href="./styles/summary.css" />
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
      <li><a href="skills.php">4 Skills</a></li>
      <li><a href="language.php">5 Language</a></li>
      <li class="active"><a href="summary.php">6 Summary</a></li>
      <li><a href="finalize.php">7 Finalize</a></li>
    </ul>

    <button class="logout-btn" onclick="window.location.href='index.html'">Logout</button>
  </aside>

  
  <main class="builder-content">
    <h1>Summarize Your Professional Profile</h1>
    <p>This section highlights your experience, achievements, and goals in one powerful summary.</p>

    <form id="summaryForm" method="POST">
      <div class="form-group">
        <label>Professional Headline</label>
        <input type="text" id="headline" name="headline" placeholder="e.g., Researcher | Materials Engineer | AI Enthusiast" required>
      </div>

      <div class="form-group">
        <label>About You / Professional Summary</label>
        <textarea id="about" name="about" rows="4" placeholder="Write a concise paragraph about your background, skills, and goals..." required></textarea>
      </div>

      <div class="form-group">
        <label>Career Objective</label>
        <textarea id="objective" name="objective" rows="3" placeholder="What do you aim to achieve in your next role or research project?"></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Years of Experience</label>
          <input type="number" id="years" name="years_experience" min="0" max="50" placeholder="e.g., 3">
        </div>
        <div class="form-group">
          <label>Key Strengths</label>
          <input type="text" id="strengths" name="strengths" placeholder="e.g., Leadership, Problem Solving, Creativity">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Soft Skills</label>
          <input type="text" id="softSkills" name="soft_skills" placeholder="e.g., Communication, Adaptability, Teamwork">
        </div>
        <div class="form-group">
          <label>Hobbies / Interests</label>
          <input type="text" id="hobbies" name="hobbies" placeholder="e.g., Reading, Cricket, Badminton">
        </div>
      </div>

      <div class="form-group">
        <label>Awards / Certifications</label>
        <textarea id="achievements" name="achievements" rows="3" placeholder="e.g., HKPFS 2025 Fellow, Best Paper Award 2024"></textarea>
      </div>

      <div class="form-group">
        <label>Projects / Contributions</label>
        <textarea id="projects" name="projects" rows="3" placeholder="e.g., Developed AI-based fire detection system using multi-sensor fusion."></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>LinkedIn URL</label>
          <input type="url" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/yourname">
        </div>
        <div class="form-group">
          <label>Portfolio / GitHub</label>
          <input type="url" id="portfolio" name="portfolio" placeholder="https://github.com/username">
        </div>
      </div>

      <div class="button-row">
        <button type="button" class="btn-prev">← Back</button>
        <button type="submit" class="btn-save">Save</button>
        <button type="button" id="addAnotherBtn">+ Add Another</button>
        <button type="button" class="btn-next">Next →</button>
      </div>
    </form>

    
    <?php if (!empty($summary)): ?>
    <div class="existing-summary" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
      <h3>Your Saved Summaries</h3>
      <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Manage your saved professional summaries:</p>
      <div class="summary-list">
        <?php foreach ($summary as $index => $sum): ?>
          <div class="summary-item" style="background: white; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #6a4cff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
              <div style="flex: 1;">
                <strong style="font-size: 16px; color: #333; display: block;"><?= htmlspecialchars($sum['headline'] ?? 'Professional Summary') ?></strong>
                <div style="color: #666; font-size: 14px; line-height: 1.4; margin-top: 8px;">
                  <?= htmlspecialchars(substr($sum['about'] ?? '', 0, 150)) ?><?= strlen($sum['about'] ?? '') > 150 ? '...' : '' ?>
                </div>
                <div style="color: #888; font-size: 13px; margin-top: 8px;">
                  <?php if (!empty($sum['years_experience'])): ?>
                    <span style="margin-right: 15px;">📅 <?= $sum['years_experience'] ?> years</span>
                  <?php endif; ?>
                  <?php if (!empty($sum['strengths'])): ?>
                    <span>💪 <?= htmlspecialchars(substr($sum['strengths'], 0, 50)) ?><?= strlen($sum['strengths']) > 50 ? '...' : '' ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <button type="button" class="btn-delete" onclick="deleteSummary(<?= $index ?>)" style="background: #ff4757; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s; margin-left: 15px;">Delete</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="margin-top: 15px; text-align: center;">
        <button type="button" onclick="deleteAllSummary()" style="background: #ff6b6b; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s;">Delete All Summaries</button>
      </div>
    </div>
    <?php endif; ?>
  </main>

 
  <aside class="preview">
    <video autoplay muted loop id="previewBg">
      <source src="bg_section.mp4" type="video/mp4">
    </video>
    <div class="card">
      <h2 id="previewName"><?php echo htmlspecialchars($userName); ?></h2>
      <p id="previewHeadline" class="headline">Your Professional Headline</p>
      <p id="previewAbout">Your professional summary will appear here...</p>
      <p id="previewObjective" class="objective" style="display:none;"></p>
      <p id="previewExperience" class="experience" style="display:none;"></p>
      <div id="previewStrengths" class="tags"></div>
      <div id="previewAchievements" class="achievements"></div>
    </div>
  </aside>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const form = document.getElementById("summaryForm");
const addBtn = document.getElementById("addAnotherBtn");
const headline = document.getElementById("headline");
const about = document.getElementById("about");
const objective = document.getElementById("objective");
const years = document.getElementById("years");
const strengths = document.getElementById("strengths");
const softSkills = document.getElementById("softSkills");
const hobbies = document.getElementById("hobbies");
const achievements = document.getElementById("achievements");
const projects = document.getElementById("projects");
const linkedin = document.getElementById("linkedin");
const portfolio = document.getElementById("portfolio");


const prevHeadline = document.getElementById("previewHeadline");
const prevAbout = document.getElementById("previewAbout");
const prevObjective = document.getElementById("previewObjective");
const prevExperience = document.getElementById("previewExperience");
const prevStrengths = document.getElementById("previewStrengths");
const prevAchievements = document.getElementById("previewAchievements");


function updatePreview() {
  
  prevHeadline.textContent = headline.value || "Your Professional Headline";
  
  
  prevAbout.textContent = about.value || "Your professional summary will appear here...";
  
  if (objective.value.trim()) {
    prevObjective.textContent = "🎯 " + objective.value;
    prevObjective.style.display = "block";
  } else {
    prevObjective.style.display = "none";
  }
  
 
  if (years.value.trim()) {
    prevExperience.textContent = "📅 " + years.value + " years of experience";
    prevExperience.style.display = "block";
  } else {
    prevExperience.style.display = "none";
  }
  
  
  const strengthsArray = strengths.value.split(',').map(s => s.trim()).filter(Boolean);
  prevStrengths.innerHTML = strengthsArray.map(s => `<span class="tag">${s}</span>`).join(' ');
  
 
  const achievementsArray = achievements.value.split(',').map(a => a.trim()).filter(Boolean);
  prevAchievements.innerHTML = achievementsArray.map(a => `<p>🏆 ${a}</p>`).join('');
}

[headline, about, objective, years, strengths, achievements].forEach(el => {
  el.addEventListener("input", updatePreview);
});


form.addEventListener("submit", async (e) => {
  e.preventDefault();
  
  const formData = new FormData(form);
  const headlineVal = headline.value.trim();
  const aboutVal = about.value.trim();

  
  if (!headlineVal || !aboutVal) {
    Swal.fire({
      icon: "warning",
      title: "Missing Required Fields",
      text: "Please fill Professional Headline and About sections!",
      confirmButtonColor: "#6a4cff"
    });
    return;
  }

  try {
    const res = await fetch("save_summary.php", { method: "POST", body: formData });
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
      title: "Error saving summary",
      text: "Please check your connection.",
      confirmButtonColor: "#6a4cff"
    });
  }
});


addBtn.addEventListener("click", async (e) => {
  e.preventDefault();
  
  
  const headlineVal = headline.value.trim();
  const aboutVal = about.value.trim();
  
  if (!headlineVal || !aboutVal) {
    Swal.fire({
      icon: "warning",
      title: "Missing Information",
      text: "Please fill Professional Headline and About sections before adding another entry.",
      confirmButtonColor: "#6a4cff"
    });
    return;
  }
  

  const formData = new FormData();
  formData.append('headline', headlineVal);
  formData.append('about', aboutVal);
  formData.append('objective', objective.value);
  formData.append('years_experience', years.value);
  formData.append('strengths', strengths.value);
  formData.append('soft_skills', softSkills.value);
  formData.append('hobbies', hobbies.value);
  formData.append('achievements', achievements.value);
  formData.append('projects', projects.value);
  formData.append('linkedin', linkedin.value);
  formData.append('portfolio', portfolio.value);
  
  try {
    const res = await fetch("save_summary.php", { 
      method: "POST", 
      body: formData 
    });
    const data = await res.json();

    if (data.status === "success") {
      
      headline.value = '';
      about.value = '';
      objective.value = '';
      years.value = '';
      strengths.value = '';
      softSkills.value = '';
      hobbies.value = '';
      achievements.value = '';
      projects.value = '';
      linkedin.value = '';
      portfolio.value = '';
      updatePreview();
      
      Swal.fire({
        toast: true,
        icon: "success",
        title: "Summary saved!",
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


async function deleteSummary(index) {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "This summary entry will be removed from your profile",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_summary.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `index=${index}`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "Summary entry deleted successfully!", 
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


async function deleteAllSummary() {
  const result = await Swal.fire({
    title: 'Delete all summary entries?',
    text: "This will remove all your saved professional summaries permanently",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete all!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_summary.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `delete_all=true`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "All summary entries deleted successfully!", 
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
  window.location.href = "language.php";
});
document.querySelector(".btn-next").addEventListener("click", () => {
  window.location.href = "finalize.php";
});


updatePreview();
</script>
</body>
</html>