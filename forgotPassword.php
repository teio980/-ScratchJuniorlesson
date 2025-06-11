<?php
include 'reshead.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="cssfile/forgotPassword.css">
  <link rel="stylesheet" href="cssfile/header.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
  <title>Reset Password</title>
</head>
<body>

  <div class="main">
    <div class="form">
      <h2>Reset Your Password</h2>
      <p class="form-subtext">An email will be sent to you to reset your password.</p>

      <form action="includes/forgotPassword.php" method="POST" class="form_content">
        <input type="email" name="email" placeholder="Enter your email address..." required>
        <button type="submit" name="reset_password_button">Submit</button>
      </form>
    </div>
  </div>

  <script>
    function showSidebar() {
      document.querySelector('.side').classList.add('show');
    }

    function hideSidebar() {
      document.querySelector('.side').classList.remove('show');
    }
  </script>
</body>
</html>
