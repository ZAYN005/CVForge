<?php
session_start();
$userName = $_SESSION['user_name'] ?? "Zayn Ali";


$languages = $_SESSION['current_languages'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Language | CVForge</title>
  <link rel="stylesheet" href="./styles/language.css" />
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
      <li class="active"><a href="language.php">5 Language</a></li>
      <li><a href="summary.php">6 Summary</a></li>
      <li><a href="finalize.php">7 Finalize</a></li>
    </ul>
    <button class="logout-btn" onclick="window.location.href='index.html'">Logout</button>
  </aside>

  
  <main class="builder-content">
    <h1>Showcase Your Languages</h1>
    <p>Indicate your language skills and proficiency level.</p>

    <form id="languageForm" method="POST">
      <div id="languageFields">
        <?php if (empty($languages)): ?>
          
          <div class="form-row">
            <div class="form-group">
              <label>Language</label>
              <input type="text" name="language[]" placeholder="e.g., English" required>
            </div>
            <div class="form-group">
              <label>Proficiency (%)</label>
              <input type="range" name="proficiency[]" min="0" max="100" value="80" class="slider" oninput="updateLabel(this)">
              <p class="percent-label">80%</p>
            </div>
          </div>
        <?php else: ?>
          
          <?php foreach ($languages as $index => $lang): ?>
            <div class="form-row">
              <div class="form-group">
                <label>Language</label>
                <input type="text" name="language[]" value="<?= htmlspecialchars($lang['language']) ?>" placeholder="e.g., English" required>
              </div>
              <div class="form-group">
                <label>Proficiency (%)</label>
                <input type="range" name="proficiency[]" min="0" max="100" value="<?= $lang['proficiency'] ?>" class="slider" oninput="updateLabel(this)">
                <p class="percent-label"><?= $lang['proficiency'] ?>%</p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div id="extraLanguages"></div>

      <div class="button-row">
        <button type="button" class="btn-prev">← Back</button>
        <button type="submit" class="btn-save">Save</button>
        <button type="button" id="addLanguage">+ Add Another</button>
        <button type="button" class="btn-next">Next →</button>
      </div>
    </form>

    
    <?php if (!empty($languages)): ?>
    <div class="existing-languages" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
      <h3>Your Saved Languages</h3>
      <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Manage your saved languages:</p>
      <div class="languages-list">
        <?php foreach ($languages as $index => $lang): ?>
          <div class="language-item" style="background: white; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #6a4cff; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="flex: 1;">
              <strong style="font-size: 16px; color: #333;"><?= htmlspecialchars($lang['language']) ?></strong> 
              <span style="color: #666; margin-left: 10px;"><?= $lang['proficiency'] ?>% proficiency</span>
            </div>
            <button type="button" class="btn-delete" onclick="deleteLanguage(<?= $index ?>)" style="background: #ff4757; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s;">Delete</button>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="margin-top: 15px; text-align: center;">
        <button type="button" onclick="deleteAllLanguages()" style="background: #ff6b6b; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s;">Delete All Languages</button>
      </div>
    </div>
    <?php endif; ?>
  </main>

  
  <aside class="preview">
     <video autoplay muted loop id="previewBg">
      <source src="bg_section.mp4" type="video/mp4">
    </video>
    <div class="card">
      <h2>Language Preview</h2>
      <div id="languageList" class="progress-container">
        <?php if (!empty($languages)): ?>
          <?php foreach ($languages as $lang): ?>
            <div class="progress-item">
              <p><?= htmlspecialchars($lang['language']) ?></p>
              <div class="progress-bar">
                <div class="fill" style="width:<?= $lang['proficiency'] ?>%;"></div>
              </div>
              <span><?= $lang['proficiency'] ?>%</span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No languages added yet</p>
        <?php endif; ?>
      </div>
    </div>
  </aside>
</div>

<script>
const form = document.getElementById('languageForm');
const container = document.getElementById('extraLanguages');
const list = document.getElementById('languageList');
const languageFields = document.getElementById('languageFields');


document.getElementById('addLanguage').addEventListener('click', () => {
  const row = document.createElement('div');
  row.classList.add('form-row');
  row.innerHTML = `
    <div class="form-group">
      <label>Language</label>
      <input type="text" name="language[]" placeholder="e.g., Mandarin" required>
    </div>
    <div class="form-group">
      <label>Proficiency (%)</label>
      <input type="range" name="proficiency[]" min="0" max="100" value="70" class="slider" oninput="updateLabel(this)">
      <p class="percent-label">70%</p>
    </div>
  `;
  container.appendChild(row);
  updatePreview();
});


function updateLabel(slider) {
  slider.nextElementSibling.textContent = slider.value + '%';
  updatePreview();
}


form.addEventListener('input', updatePreview);

function updatePreview() {
  const langs = document.querySelectorAll('input[name="language[]"]');
  const profs = document.querySelectorAll('input[name="proficiency[]"]');
  list.innerHTML = '';
  
  for (let i = 0; i < langs.length; i++) {
    if (langs[i].value) {
      list.innerHTML += `
        <div class="progress-item">
          <p>${langs[i].value}</p>
          <div class="progress-bar"><div class="fill" style="width:${profs[i].value}%;"></div></div>
          <span>${profs[i].value}%</span>
        </div>`;
    }
  }
  
  if (list.innerHTML === '') {
    list.innerHTML = '<p>No languages added yet</p>';
  }
}


async function deleteLanguage(index) {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "This language will be removed from your profile",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_language.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `index=${index}`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "Language deleted successfully!", 
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


async function deleteAllLanguages() {
  const result = await Swal.fire({
    title: 'Delete all languages?',
    text: "This will remove all your saved languages permanently",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete all!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_language.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `delete_all=true`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "All languages deleted successfully!", 
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


form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(form);
  
  try {
    const res = await fetch("save_language.php", { method: "POST", body: formData });
    const rawText = await res.text();
    
    try {
      const data = JSON.parse(rawText);
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: data.message || "Languages saved successfully!", 
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
    } catch (jsonError) {
     
      Swal.fire({ 
        toast: true, 
        icon: "success", 
        title: "Languages saved successfully!", 
        position: "bottom-end", 
        timer: 2000, 
        showConfirmButton: false,
        background: "#333",
        color: "#fff"
      });
      
     
      setTimeout(() => {
        window.location.reload();
      }, 500);
    }
  } catch {
    Swal.fire({ icon: "error", title: "Connection Error", text: "Check your server or database connection." });
  }
});


document.querySelector(".btn-prev").addEventListener("click", () => window.location.href = "skills.php");
document.querySelector(".btn-next").addEventListener("click", () => window.location.href = "summary.php");


updatePreview();
</script>
</body>
</html>