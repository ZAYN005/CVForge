<?php
session_set_cookie_params([
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

include '../cvforge_api/db_connect.php';
$user_id = $_SESSION['user_id'] ?? null;
$user = $_SESSION['username'];


$existing_resumes = [];
$user_name = "User";
$has_existing_resumes = false;

if ($user_id && $conn) {
    $stmt = $conn->prepare("SELECT * FROM resume_versions WHERE user_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_resumes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    
    if (!empty($existing_resumes)) {
        $latest_resume = $existing_resumes[0];
        $header_data = json_decode($latest_resume['header_data'], true);
        $user_name = $header_data[0]['first_name'] ?? $user;
        $has_existing_resumes = true;
    } else {
        
        $user_name = $user;
        $has_existing_resumes = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | CVForge</title>
    <link rel="stylesheet" href="./styles/dashboard.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
</head>
<body>
    <header class="navbar">
        <div class="logo"><span>CV</span>Forge</div>
        <nav class="nav-links">
            <a href="#welcome">Dashboard</a>
            <a href="feature.html">Features</a>
            <a href="resume_template.php">Templates</a>
            <a href="about.html">About</a>
            <a href="faq.html">FAQ</a>
            <a href="admin_dashboard.php">ADMIN</a>
        </nav>
        </nav>
        </nav>
        <div class="user-info">
            <span>👋 Welcome, <?php echo htmlspecialchars($user_name); ?></span>
            <a href="http://localhost/cvforge_api/logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

  
<section id="welcome" class="welcome-section">
    <div class="welcome-content" data-aos="fade-up">
        <?php if ($has_existing_resumes): ?>
            <h1>👋 Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <p class="welcome-subtitle">What would you like to do today?</p>
        <?php else: ?>
            <h1>👋 Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <p class="welcome-subtitle">Let's create your first professional resume</p>
        <?php endif; ?>
        
        <div class="action-cards">
            <?php if ($has_existing_resumes): ?>
                
                <div class="action-card" onclick="window.location.href='resume_template.php?action=new'">
                    <div class="card-icon">🆕</div>
                    <h3>Create New Resume</h3>
                    <p>Start fresh with a new resume</p>
                    <div class="card-badge">Start Here</div>
                </div>
                
                <div class="action-card" onclick="showResumeList()">
                    <div class="card-icon">✏️</div>
                    <h3>Edit Existing Resume</h3>
                    <p>Modify your previous resumes</p>
                    <div class="card-badge"><?php echo count($existing_resumes); ?> saved</div>
                </div>
                
                <div class="action-card" onclick="quickDownload()">
                    <div class="card-icon">📄</div>
                    <h3>Quick Download</h3>
                    <p>Download your latest resume instantly</p>
                </div>
            <?php else: ?>
               
                <div class="action-card" onclick="window.location.href='resume_template.php'">
                    <div class="card-icon">🎨</div>
                    <h3>View Templates</h3>
                    <p>Browse our professional resume templates</p>
                    <div class="card-badge">Recommended</div>
                </div>
                
                <div class="action-card" onclick="window.location.href='resume_template.php'">
                    <div class="card-icon">🚀</div>
                    <h3>Start Building</h3>
                    <p>Begin creating your first resume</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
   
    <?php if ($has_existing_resumes): ?>
    <section class="recent-resumes">
        <div class="section-header">
            <h2>Your Recent Resumes</h2>
            <p>Quick access to your saved resumes</p>
        </div>
        
        <div class="resumes-grid">
            <?php 
            $counter = 0;
            foreach (array_slice($existing_resumes, 0, 3) as $resume): 
                $header = json_decode($resume['header_data'], true);
                $experience = json_decode($resume['experience_data'], true);
                $education = json_decode($resume['education_data'], true);
                
                $job_title = !empty($experience) ? $experience[0]['job_title'] : 'No experience added';
                $degree = !empty($education) ? $education[0]['degree'] : 'No education added';
            ?>
            <div class="resume-card" data-aos="fade-up" data-aos-delay="<?php echo $counter * 100; ?>">
                <div class="resume-card-header">
                    <h4><?php echo htmlspecialchars($header[0]['first_name'] ?? 'Unknown'); ?>'s Resume</h4>
                    <span class="version-badge"><?php echo htmlspecialchars($resume['version_name']); ?></span>
                </div>
                
                <div class="resume-card-content">
                    <div class="resume-info-item">
                        <span class="info-label">💼 Position</span>
                        <span class="info-value"><?php echo htmlspecialchars($job_title); ?></span>
                    </div>
                    <div class="resume-info-item">
                        <span class="info-label">🎓 Education</span>
                        <span class="info-value"><?php echo htmlspecialchars($degree); ?></span>
                    </div>
                    <div class="resume-info-item">
                        <span class="info-label">🎨 Template</span>
                        <span class="info-value">#<?php echo htmlspecialchars($resume['template_used']); ?></span>
                    </div>
                </div>
                
                <div class="resume-card-actions">
                    <button class="btn-edit" onclick="editResume(<?php echo $resume['id']; ?>)">Edit</button>
                    <button class="btn-download" onclick="downloadResume(<?php echo $resume['id']; ?>)">Download</button>
                    <button class="btn-duplicate" onclick="duplicateResume(<?php echo $resume['id']; ?>)">Copy</button>
                </div>
            </div>
            <?php 
            $counter++;
            endforeach; 
            ?>
        </div>
        
        <?php if (count($existing_resumes) > 3): ?>
        <div class="view-all-container">
            <button class="btn-view-all" onclick="showResumeList()">View All Resumes (<?php echo count($existing_resumes); ?>)</button>
        </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>

  
    <section id="features" class="features">
        <h2 data-aos="fade-up">Powerful Features</h2>
        <div class="feature-grid">
            <div class="feature-card" data-aos="zoom-in">
                <img src="./icons/template.svg" alt="Smart Design">
                <h3>Smart Design</h3>
                <p>Beautiful, responsive, and ATS-friendly layouts that impress employers.</p>
            </div>

            <div class="feature-card" data-aos="zoom-in" data-aos-delay="150">
                <img src="./icons/detail.svg" alt="Auto Formatting">
                <h3>Auto Formatting</h3>
                <p>Your content auto-adjusts perfectly — no more messy spacing.</p>
            </div>

            <div class="feature-card" data-aos="zoom-in" data-aos-delay="300">
                <img src="./icons/download.svg" alt="Export PDF">
                <h3>Export PDF</h3>
                <p>Instantly export your resume in high-quality PDF or PNG format.</p>
            </div>
        </div>
    </section>

    
    <section id="templates" class="templates">
        <h2 data-aos="fade-up">Professional Templates</h2>
        <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Choose from our collection of modern, ATS-friendly designs</p>
        
        <div class="template-gallery">
            <div class="template-item" data-aos="zoom-in">
                <img src="template1.png" alt="Template 1">
                <div class="template-overlay">
                    <button onclick="window.location.href='builder.php?action=new&template=1'">Use This Template</button>
                </div>
            </div>
            <div class="template-item" data-aos="zoom-in" data-aos-delay="100">
                <img src="template2.png" alt="Template 2">
                <div class="template-overlay">
                    <button onclick="window.location.href='builder.php?action=new&template=2'">Use This Template</button>
                </div>
            </div>
            <div class="template-item" data-aos="zoom-in" data-aos-delay="200">
                <img src="template3.png" alt="Template 3">
                <div class="template-overlay">
                    <button onclick="window.location.href='builder.php?action=new&template=3'">Use This Template</button>
                </div>
            </div>
        </div>
    </section>

    
<div id="resumeListModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Your Saved Resumes</h2>
            <span class="close-modal" onclick="closeModal()">&times;</span>
        </div>
        <div class="resume-list">
            <?php foreach ($existing_resumes as $resume): 
                $header = json_decode($resume['header_data'], true);
                $experience = json_decode($resume['experience_data'], true);
                $education = json_decode($resume['education_data'], true);
                
                $job_title = !empty($experience) ? $experience[0]['job_title'] : 'No experience';
                $degree = !empty($education) ? $education[0]['degree'] : 'No education';
                $created_date = date('M j, Y', strtotime($resume['created_at'] ?? 'now'));
            ?>
            <div class="resume-item" data-resume-id="<?php echo $resume['id']; ?>">
                <div class="resume-info">
                    <div class="resume-header">
                        <h4><?php echo htmlspecialchars($header[0]['first_name'] ?? 'Unknown'); ?>'s Resume</h4>
                        <span class="resume-date">Created: <?php echo $created_date; ?></span>
                    </div>
                    <div class="resume-meta">
                        <span class="version"><?php echo htmlspecialchars($resume['version_name']); ?></span>
                        <span class="template">Template #<?php echo htmlspecialchars($resume['template_used']); ?></span>
                    </div>
                    <div class="resume-preview">
                        <span>💼 <?php echo htmlspecialchars($job_title); ?></span>
                        <span>🎓 <?php echo htmlspecialchars($degree); ?></span>
                    </div>
                </div>
                <div class="resume-actions">
                    <button class="btn-action btn-edit" onclick="editResume(<?php echo $resume['id']; ?>)">
                        <span class="btn-icon">✏️</span> Edit
                    </button>
                    <button class="btn-action btn-download" onclick="downloadResume(<?php echo $resume['id']; ?>)">
                        <span class="btn-icon">📄</span> Download
                    </button>
                    <button class="btn-action btn-duplicate" onclick="duplicateResume(<?php echo $resume['id']; ?>)">
                        <span class="btn-icon">📋</span> Copy
                    </button>
                    <button class="btn-action btn-delete" onclick="confirmDelete(<?php echo $resume['id']; ?>, '<?php echo htmlspecialchars($header[0]['first_name'] ?? 'Unknown'); ?>')">
                        <span class="btn-icon">🗑️</span> Delete
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<div id="deleteModal" class="modal">
    <div class="modal-content confirm-modal">
        <div class="modal-header">
            <h2>Confirm Delete</h2>
            <span class="close-modal" onclick="closeDeleteModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="warning-icon">⚠️</div>
            <p>Are you sure you want to delete <strong id="deleteResumeName"></strong>'s resume?</p>
            <p class="warning-text">This action cannot be undone.</p>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-confirm-delete" id="confirmDeleteBtn">Delete Resume</button>
        </div>
    </div>
</div>

    <footer class="footer">
        <p>© 2025 CVForge. All rights reserved.</p>
        <p class="tagline">Empowering careers through smart resume design.</p>
    </footer>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init();
    
    let resumeToDelete = null;

    function showResumeList() {
        document.getElementById('resumeListModal').style.display = 'block';
    }
    
    function closeModal() {
        document.getElementById('resumeListModal').style.display = 'none';
    }

    function confirmDelete(resumeId, resumeName) {
        console.log('Confirm delete called:', resumeId, resumeName);
        resumeToDelete = resumeId;
        document.getElementById('deleteResumeName').textContent = resumeName;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        resumeToDelete = null;
    }
    
    function editResume(resumeId) {
        fetch('load_resume.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({resume_id: resumeId})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'builder.php?edit=' + resumeId;
            } else {
                alert('Error loading resume');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading resume');
        });
    }
    
    function downloadResume(resumeId) {
        window.location.href = 'download_resume.php?resume_id=' + resumeId;
    }
    
    function duplicateResume(resumeId) {
        if (confirm('Create a copy of this resume?')) {
            fetch('duplicate_resume.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'resume_id=' + resumeId
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error creating copy');
                }
            });
        }
    }

    function deleteResume(resumeId) {
        console.log('Deleting resume:', resumeId);
        
        const btn = document.getElementById('confirmDeleteBtn');
        const originalText = btn.textContent;
        
        btn.classList.add('loading');
        btn.textContent = 'Deleting...';
        btn.disabled = true;

        fetch('delete_resume.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'resume_id=' + resumeId
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Delete response:', data);
            if (data.success) {
                
                const resumeItem = document.querySelector(`.resume-item[data-resume-id="${resumeId}"]`);
                if (resumeItem) {
                    resumeItem.style.opacity = '0';
                    resumeItem.style.transform = 'translateX(100px)';
                    setTimeout(() => {
                        resumeItem.remove();
                        
                        const remainingResumes = document.querySelectorAll('.resume-item');
                        if (remainingResumes.length === 0) {
                            console.log('No resumes left, reloading page...');
                            location.reload();
                        }
                    }, 300);
                }
                closeDeleteModal();
                
              
                showNotification('Resume deleted successfully', 'success');
            } else {
                showNotification(data.error || 'Error deleting resume', 'error');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showNotification('Network error deleting resume', 'error');
        })
        .finally(() => {
            btn.classList.remove('loading');
            btn.textContent = originalText;
            btn.disabled = false;
        });
    }

    function showNotification(message, type) {
        console.log('Showing notification:', message, type);
        
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            z-index: 3000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            ${type === 'success' ? 'background: linear-gradient(45deg, #00c851, #007e33);' : 'background: linear-gradient(45deg, #ff4444, #cc0000);'}
        `;
        
        document.body.appendChild(notification);
        
        
        setTimeout(() => notification.style.transform = 'translateX(0)', 100);
        
        
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
    
    function quickDownload() {
       
        <?php if ($has_existing_resumes): ?>
        downloadResume(<?php echo $existing_resumes[0]['id']; ?>);
        <?php endif; ?>
    }
    
   
    window.onclick = function(event) {
        const modal = document.getElementById('resumeListModal');
        const deleteModal = document.getElementById('deleteModal');
        
        if (event.target === modal) {
            closeModal();
        }
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    }

    
    document.addEventListener('DOMContentLoaded', function() {
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                console.log('Delete confirmed for resume:', resumeToDelete);
                if (resumeToDelete) {
                    deleteResume(resumeToDelete);
                }
            });
        }
    });
</script>
</body>
</html>

