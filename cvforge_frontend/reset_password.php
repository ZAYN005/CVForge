<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Reset Password | CVForge</title>
<link rel="stylesheet" href="forgot_password.css" />
</head>
<body>
  <div class="auth-container">
    <div class="auth-card fade-in">
      <div class="logo"><span>CV</span>Forge</div>
      <h2>Reset Your Password</h2>
      <p class="subtitle">Enter your new password below.</p>

      <form id="resetForm">
        <input type="hidden" id="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
        <div class="input-group">
          <i class="icon-lock"></i>
          <input type="password" id="newPassword" placeholder="New Password" required />
        </div>
        <div class="input-group">
          <i class="icon-lock"></i>
          <input type="password" id="confirmPassword" placeholder="Confirm Password" required />
        </div>

        <button type="submit" class="btn-primary">Update Password</button>
        <p class="back-link"><a href="login.html">⬅ Back to Login</a></p>
      </form>

      <div id="messageBox"></div>
    </div>
  </div>

  <script>
    const form = document.getElementById("resetForm");
    const messageBox = document.getElementById("messageBox");

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const token = document.getElementById("token").value;
      const pass1 = document.getElementById("newPassword").value;
      const pass2 = document.getElementById("confirmPassword").value;

      if (pass1 !== pass2) {
        messageBox.innerHTML = "<p class='error'>❌ Passwords do not match.</p>";
        return;
      }

      messageBox.innerHTML = "<p class='loading'>⏳ Updating...</p>";

      const res = await fetch("http://localhost/cvforge_api/reset_password.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `token=${encodeURIComponent(token)}&password=${encodeURIComponent(pass1)}`
      });

      const data = await res.json();

      if (data.status === "success") {
        messageBox.innerHTML = "<p class='success'>✅ Password updated successfully!</p>";
        setTimeout(() => window.location.href = "login.html", 1500);
      } else {
        messageBox.innerHTML = `<p class='error'>⚠️ ${data.message}</p>`;
      }
    });
  </script>
</body>
</html>
