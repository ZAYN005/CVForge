<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password | CVForge</title>
  <link rel="stylesheet" href="./styles/forgot_password.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
  <div class="auth-container">
    <div class="auth-card fade-in">
      <div class="logo">
        <span>CV</span>Forge
      </div>
      <h2>Forgot Password?</h2>
      <p class="subtitle">Enter your registered email address to receive a password reset link.</p>

      <form id="forgotForm">
        <div class="input-group">
          <i class="icon-envelope"></i>
          <input type="email" id="email" name="email" placeholder="Enter your email" required />
        </div>

        <button type="submit" class="btn-primary">Send Reset Link</button>
        <p class="back-link">
          <a href="login.html"><i class="icon-arrow-left"></i> Back to Sign In</a>
        </p>
      </form>

      <div id="messageBox"></div>
    </div>
  </div>

  <script>
    const form = document.getElementById("forgotForm");
    const messageBox = document.getElementById("messageBox");

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = document.getElementById("email").value.trim();

      if (!email) return alert("Please enter your email.");

      messageBox.innerHTML = "<p class='loading'>⏳ Sending reset link...</p>";

      try {
        const res = await fetch("http://localhost/cvforge_api/forgot_password.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `email=${encodeURIComponent(email)}`
        });

        const data = await res.json();

        if (data.status === "success") {
          messageBox.innerHTML = "<p class='success'>✅ Reset link sent! Check your inbox.</p>";
        } else {
          messageBox.innerHTML = `<p class='error'>⚠️ ${data.message || "Error sending email."}</p>`;
        }
      } catch (err) {
        console.error(err);
        messageBox.innerHTML = "<p class='error'>❌ Server connection error.</p>";
      }
    });
  </script>
</body>
</html>
