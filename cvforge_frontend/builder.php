<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = "Zayn Ali";
}

$user_id = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

$editing_mode = isset($_GET['edit']);
$editing_resume_id = $_SESSION['editing_resume_id'] ?? null;


if (isset($_GET['action']) && $_GET['action'] === 'new') {
    unset($_SESSION['editing_resume_id']);

    if (!isset($_GET['keep_data'])) {
        unset($_SESSION['current_header']);
        unset($_SESSION['current_experiences']);
        unset($_SESSION['current_education']);
        unset($_SESSION['current_skills']);
        unset($_SESSION['current_languages']);
        unset($_SESSION['current_summary']);
    }
}


if (isset($_GET['template'])) {
    $_SESSION['selected_template'] = intval($_GET['template']);
}


$header = $_SESSION['current_header'] ?? [];
$current_header = $header[0] ?? []; 


include '../cvforge_api/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>CVForge Builder</title>
  <link rel="stylesheet" href="./styles/builder.css">
</head>
<body>
<div class="builder-container">

  
  <aside class="sidebar">
    <div class="brand">
      <h2>CVForge</h2>
      <div class="welcome-box">
        <p>👋 Welcome, <b><?php echo htmlspecialchars($userName ?? "Guest"); ?></b></p>
        <hr class="divider">
      </div>
    </div>

    <ul class="steps">
      <li class="active"><a href="builder.php">1 Header</a></li>
      <li><a href="experience.php">2 Experience</a></li>
      <li><a href="education.php">3 Education</a></li>
      <li><a href="skills.php">4 Skills</a></li>
      <li><a href="language.php">5 Language</a></li>
      <li><a href="summary.php">6 Summary</a></li>
      <li><a href="finalize.php">7 Finalize</a></li>
    </ul>

    <button class="logout-btn" onclick="window.location.href='index.html'">Logout</button>
  </aside>

 
  <main class="builder-content">
    
    
    <h1 style="color: #6a4cff; font-size: 28px; margin-bottom: 10px;">Let's start with your header</h1>
    
<?php if (isset($editing_mode) && $editing_mode): ?>
    <p style="background: #f8f9ff; padding: 15px; border-radius: 8px; border-left: 4px solid #6a4cff; margin-bottom: 20px; color: #333;">
        <strong>Editing Mode:</strong> You are editing an existing resume. Changes will be saved as a new version when you finalize or download.
    </p>
    <button class="editing-back-btn" onclick="window.location.href='home.php'" style="background: #6a4cff; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; transition: all 0.3s ease;">
        Back to Dashboard
    </button>
<?php endif; ?>

    <h1>Let's start with your header</h1>
    <p>Include your full name and multiple ways for employers to reach you.</p>

    <form action="save_header.php" method="POST" enctype="multipart/form-data" id="headerForm">
      
      <?php if ($editing_mode && $editing_resume_id): ?>
      <input type="hidden" name="editing_resume_id" value="<?php echo $editing_resume_id; ?>">
      <?php endif; ?>

      <div class="form-row">
        <div class="form-group">
          <label>First Name</label>
          <input type="text" id="firstName" name="first_name" 
                 value="<?= htmlspecialchars($current_header['first_name'] ?? '') ?>" 
                 placeholder="Enter Your First Name" />
        </div>
        <div class="form-group">
          <label>Last Name</label>
          <input type="text" id="lastName" name="last_name" 
                 value="<?= htmlspecialchars($current_header['last_name'] ?? '') ?>" 
                 placeholder="Your Last Name" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>City</label>
          <input type="text" id="city" name="city" 
                 value="<?= htmlspecialchars($current_header['city'] ?? '') ?>" 
                 placeholder="Enter Your City" />
        </div>
        <div class="form-group">
          <label>State</label>
          <input type="text" id="state" name="state" 
                 value="<?= htmlspecialchars($current_header['state'] ?? '') ?>" 
                 placeholder="State" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Zip Code</label>
          <input type="text" id="zip" name="zip" 
                 value="<?= htmlspecialchars($current_header['zip'] ?? '') ?>" 
                 placeholder="Zip Code" />
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" id="email" name="email" 
                 value="<?= htmlspecialchars($current_header['email'] ?? '') ?>" 
                 placeholder="Email" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Phone</label>
          <input type="text" id="phone" name="phone" 
                 value="<?= htmlspecialchars($current_header['phone'] ?? '') ?>" 
                 placeholder="Phone" />
        </div>
      </div>
      
      <div class="form-group">
        <label>Upload Profile Picture:</label>
        <input type="file" name="profile_image" accept="image/*">
        <?php if (!empty($current_header['profile_image'])): ?>
          <p>Current: <?= htmlspecialchars($current_header['profile_image']) ?></p>
        <?php endif; ?>
      </div>

      <div class="button-row">
        <button type="submit" class="btn-save">Save</button>
        <button type="button" class="btn-next">Next →</button>
      </div>
    </form>

    <?php if (!empty($header)): ?>
    <div class="existing-header" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
      <h3>Your Saved Header</h3>
      <p style="color: #666; font-size: 14px; margin-bottom: 15px;">Manage your saved header information:</p>
      <div class="header-list">
        <?php foreach ($header as $index => $head): ?>
          <div class="header-item" style="background: white; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #6a4cff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
              <div style="flex: 1;">
                <strong style="font-size: 16px; color: #333; display: block;">
                  <?= htmlspecialchars($head['first_name'] ?? '') ?> <?= htmlspecialchars($head['last_name'] ?? '') ?>
                </strong>
                <span style="color: #666; font-size: 14px;">
                  <?= htmlspecialchars($head['city'] ?? '') ?>, <?= htmlspecialchars($head['state'] ?? '') ?> <?= htmlspecialchars($head['zip'] ?? '') ?>
                </span>
                <div style="color: #888; font-size: 13px; display: block; margin-top: 5px;">
                  <?= htmlspecialchars($head['email'] ?? '') ?> | <?= htmlspecialchars($head['phone'] ?? '') ?>
                </div>
                <?php if (!empty($head['profile_image'])): ?>
                  <div style="color: #666; font-size: 13px; margin-top: 5px;">
                    Profile Image: <?= htmlspecialchars($head['profile_image']) ?>
                  </div>
                <?php endif; ?>
              </div>
              <button type="button" class="btn-delete" onclick="deleteHeader(<?= $index ?>)" style="background: #ff4757; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s; margin-left: 15px;">Delete</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="margin-top: 15px; text-align: center;">
        <button type="button" onclick="deleteAllHeader()" style="background: #ff6b6b; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background 0.3s;">Delete Header</button>
      </div>
    </div>
    <?php endif; ?>
  </main>

  
  <aside class="preview">
    <video autoplay muted loop id="previewBg">
      <source src="bg_section.mp4" type="video/mp4">
    </video>

    <div class="card">
      <h2 id="previewName">
        <?= htmlspecialchars(($current_header['first_name'] ?? 'Your') . ' ' . ($current_header['last_name'] ?? 'Name')) ?>
      </h2>
      <p id="previewDetails">
        <?= htmlspecialchars(($current_header['city'] ?? 'City') . ', ' . ($current_header['state'] ?? 'State')) ?> | 
        <?= htmlspecialchars($current_header['phone'] ?? 'Phone') ?> | 
        <?= htmlspecialchars($current_header['email'] ?? 'Email') ?>
      </p>
      <h3>Summary</h3>
      <p>Your summary will appear here...</p>
    </div>
  </aside>

</div>


<script>

document.getElementById('headerForm').addEventListener('submit', async function(e) {
    e.preventDefault(); 
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('save_header.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            
            Swal.fire({
                toast: true,
                icon: 'success',
                title: data.message,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                background: '#333',
                color: '#fff'
            }).then(() => {
               
                window.location.reload();
            });
        } else {
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonColor: '#6a4cff'
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Connection Error',
            text: 'Please check your internet connection.',
            confirmButtonColor: '#6a4cff'
        });
    }
});


function updatePreview() {
  const first = document.getElementById('firstName').value || 'Your';
  const last = document.getElementById('lastName').value || 'Name';
  const city = document.getElementById('city').value || 'City';
  const state = document.getElementById('state').value || 'State';
  const phone = document.getElementById('phone').value || 'Phone';
  const email = document.getElementById('email').value || 'Email';

  document.getElementById('previewName').textContent = `${first} ${last}`;
  document.getElementById('previewDetails').textContent = `${city}, ${state} | ${phone} | ${email}`;
}


['firstName', 'lastName', 'city', 'state', 'phone', 'email'].forEach(id => {
  document.getElementById(id).addEventListener('input', updatePreview);
});


async function deleteHeader(index) {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "This header information will be removed from your profile",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_header.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `index=${index}`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "Header information deleted successfully!", 
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


async function deleteAllHeader() {
  const result = await Swal.fire({
    title: 'Delete header information?',
    text: "This will remove all your saved header information permanently",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ff4757',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  });

  if (result.isConfirmed) {
    try {
      const res = await fetch("delete_header.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `delete_all=true`
      });
      
      const data = await res.json();
      
      if (data.status === "success") {
        Swal.fire({ 
          toast: true, 
          icon: "success", 
          title: "Header information deleted successfully!", 
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


document.querySelector(".btn-next").addEventListener("click", async function(e) {
    e.preventDefault();
    
   
    const submitEvent = new Event('submit');
    document.getElementById('headerForm').dispatchEvent(submitEvent);
    
    
    setTimeout(() => {
        window.location.href = 'experience.php';
    }, 1000);
});


updatePreview();
</script>

</body>
</html>

