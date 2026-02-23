<?php 
session_start();
include 'db_connect.php';


$templates_query = mysqli_query($conn, "SELECT * FROM templates WHERE is_active = 1 ORDER BY template_number");
$database_templates = [];
while ($template = mysqli_fetch_assoc($templates_query)) {
    $database_templates[] = $template;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Templates | CVForge</title>
  <link rel="stylesheet" href="./styles/resume template.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<header class="navbar">
    <div class="logo"><span>CV</span>Forge</div>

    <nav class="nav-links" id="navMenu">
        <a href="home.php">Home</a>
        <a href="#" class="active">Templates</a>
        <a href="feature.html">Features</a>
        <a href="faq.html">FAQ</a>
    </nav>

    <div class="nav-buttons">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php" class="btn-outline">Dashboard</a>
            <a href="logout.php" class="btn-primary">Logout</a>
        <?php else: ?>
            <a href="login.html" class="btn-outline">Sign In</a>
            <a href="signup.html" class="btn-primary">Get Started</a>
        <?php endif; ?>
    </div>
</header>


<section class="templates-section">
  <h1>Choose Your Perfect Template</h1>
  <p class="subtitle">Preview and customize a design to create your professional resume.</p>

  <div class="templates-grid">
      
      <div class="template-card" data-id="1" data-name="Modern Minimal" data-desc="Clean and elegant layout for professionals." data-image="./pictures/template1.png">
        <img src="./pictures/template1.png" onerror="this.src='https://via.placeholder.com/300x400/4361ee/ffffff?text=Modern+Minimal'">
        <button class="preview-btn">Preview</button>
      </div>

      <div class="template-card" data-id="2" data-name="Creative Bold" data-desc="Dynamic and bold design for creatives." data-image="./pictures/template2.png">
        <img src="./pictures/template2.png" onerror="this.src='https://via.placeholder.com/300x400/4cc9f0/ffffff?text=Creative+Bold'">
        <button class="preview-btn">Preview</button>
      </div>

      <div class="template-card" data-id="3" data-name="Classic Elegance" data-desc="Timeless structure with clean typography." data-image="./pictures/template3.png">
        <img src="./pictures/template3.png" onerror="this.src='https://via.placeholder.com/300x400/f8961e/ffffff?text=Classic+Elegance'">
        <button class="preview-btn">Preview</button>
      </div>

      <div class="template-card" data-id="4" data-name="Dark Edge" data-desc="Stylish and professional dark theme design." data-image="./pictures/template4.png">
        <img src="./pictures/template4.png" onerror="this.src='https://via.placeholder.com/300x400/3f37c9/ffffff?text=Dark+Edge'">
        <button class="preview-btn">Preview</button>
      </div>

      <div class="template-card" data-id="5" data-name="Creative Blue" data-desc="Dark blue creative theme." data-image="./pictures/template5.png">
        <img src="./pictures/template5.png" onerror="this.src='https://via.placeholder.com/300x400/f72585/ffffff?text=Creative+Blue'">
        <button class="preview-btn">Preview</button>
      </div>

      <div class="template-card" data-id="6" data-name="Standard Black" data-desc="Black & white clean resume." data-image="./pictures/template6.png">
        <img src="./pictures/template6.png" onerror="this.src='https://via.placeholder.com/300x400/6c757d/ffffff?text=Standard+Black'">
        <button class="preview-btn">Preview</button>
      </div>

     
      <?php foreach ($database_templates as $template): ?>
      <div class="template-card" 
           data-id="<?php echo $template['id']; ?>" 
           data-name="<?php echo htmlspecialchars($template['template_name']); ?>" 
           data-desc="<?php echo htmlspecialchars($template['template_description'] ?? 'Professional resume template'); ?>" 
           data-image="<?php echo $template['image_path']; ?>">
        <img src="<?php echo $template['image_path']; ?>" 
             alt="<?php echo htmlspecialchars($template['template_name']); ?>" 
             onerror="this.src='https://via.placeholder.com/300x400/4361ee/ffffff?text=Template+<?php echo $template['template_number']; ?>'">
        <button class="preview-btn">Preview</button>
      </div>
      <?php endforeach; ?>
  </div>
</section>



<div id="previewModal" class="modal">
  <div class="modal-content">
    <button id="closePreview" class="close-btn">&times;</button>

    <div class="preview-wrapper">
      <img id="previewImg" src="">
    </div>

    <div class="zoom-control">
      <label>Zoom:</label>
      <input type="range" id="zoomRange" min="10" max="100" value="100">
      <span id="zoomValue">100%</span>
    </div>

    <h2 id="previewName"></h2>
    <p id="previewDesc"></p>

    <div class="modal-buttons">
      <button id="prevBtn">◀ Prev</button>
      <button id="useBtn">Use Template</button>
      <button id="nextBtn">Next ▶</button>
    </div>
  </div>
</div>

<script>

const modal = document.getElementById("previewModal");
const previewImg = document.getElementById("previewImg");
const previewName = document.getElementById("previewName");
const previewDesc = document.getElementById("previewDesc");
const useBtn = document.getElementById("useBtn");
const closeBtn = document.getElementById("closePreview");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");

let templates = document.querySelectorAll(".template-card");
let currentId = 1;


document.querySelectorAll(".preview-btn").forEach(btn => {
  btn.addEventListener("click", e => {
    const card = e.target.closest(".template-card");
    currentId = parseInt(card.dataset.id);
    openModal(card);
  });
});

function openModal(card) {
  previewImg.src = card.dataset.image;
  previewName.textContent = card.dataset.name;
  previewDesc.textContent = card.dataset.desc;

  modal.classList.add("show");
  resetZoom();

  useBtn.onclick = () => useTemplate(card.dataset.id);
}


closeBtn.onclick = () => modal.classList.remove("show");

window.addEventListener("click", (e) => {
  if (e.target === modal) {
    modal.classList.remove("show");
  }
});


function navigate(dir) {
  currentId += dir;
  

  const templatesArray = Array.from(templates);
  const currentIndex = templatesArray.findIndex(t => parseInt(t.dataset.id) === currentId);
  
  if (currentIndex === -1) {

    if (dir > 0) {
      currentId = parseInt(templatesArray[0].dataset.id);
    } else {
      currentId = parseInt(templatesArray[templatesArray.length - 1].dataset.id);
    }
  }
  
  const card = templatesArray.find(t => parseInt(t.dataset.id) === currentId);
  if (card) {
    openModal(card);
  }
}

prevBtn.onclick = () => navigate(-1);
nextBtn.onclick = () => navigate(1);


const zoomRange = document.getElementById("zoomRange");
const zoomValue = document.getElementById("zoomValue");

zoomRange.addEventListener("input", () => {
  const zoom = zoomRange.value;
  previewImg.style.transform = `scale(${zoom / 100})`;
  zoomValue.textContent = zoom + "%";
});

function resetZoom() {
  zoomRange.value = 100;
  zoomValue.textContent = "100%";
  previewImg.style.transform = "scale(1)";
}


function useTemplate(templateId) {
    let loggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

    if (!loggedIn) {
        Swal.fire({
            icon: "info",
            title: "Login Required",
            text: "Please login to use this template.",
            confirmButtonText: "Login"
        }).then(() => {
            window.location.href = `login.html?template=${templateId}&redirect=builder.php`;
        });
        return;
    }

   
    fetch("http://localhost/cvforge_api/save_template_choice.php", {
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ template_id: templateId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            window.location.href = "builder.php?template=" + templateId;
        } else {
            Swal.fire("Error", data.message, "error");
        }
    })
    .catch(() => {
        Swal.fire("Server Error", "Unable to connect to server.", "error");
    });
}


document.addEventListener('DOMContentLoaded', function() {
    templates = document.querySelectorAll(".template-card");
});
</script>

</body>
</html>