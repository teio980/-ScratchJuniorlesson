<?php
include 'reshead.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssfile/login.css">
    <link rel="stylesheet" href="cssfile/header.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Login</title>
    
</head>
<body>
<div class="main">
    <div class="right">
      <div class="login">
        <h2>Welcome back</h2>
        <p>Please enter your account details</p>
        
        <form action="includes/loginFunction.php" method="POST">
            <label for="U_Username">Username:</label>
            <input type="text" name="U_Username" id="U_Username" placeholder="Username" required>

            <label for="U_Password">Password:</label>
            <div class="password-box">
            <input type="password" id="U_Password" name="U_Password" placeholder="Password" required>
            <span class="material-symbols-outlined" id="showPassword_icon" onclick="showPassword()">visibility_off</span>
            </div>

            <div class="link">
            <a href="forgotPassword.php">Forgot Password?</a>
            <a href="register.php">Register</a>
            </div>

            <button type="submit">Login</button>
        </form>
        </div>

    </div>
  </div>
    <script>
        function showSidebar() {
            document.querySelector('.side').classList.add('show');
        }

        function hideSidebar() {
            document.querySelector('.side').classList.remove('show');
        }

        function showPassword() {
            const passwordInput = document.getElementById('U_Password');
            const icon = document.getElementById('showPassword_icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.textContent = 'visibility'; 
            } else {
                passwordInput.type = 'password';
                icon.textContent = 'visibility_off'; 
            }
        }

        function showConfirmedPassword() {
            const confirmedPasswordInput = document.getElementById('U_Confirmed_Password');
            const icon = document.getElementById('showConfirmedPassword_icon');

            if (confirmedPasswordInput.type === 'password') {
                confirmedPasswordInput.type = 'text';
                icon.textContent = 'visibility'; 
            } else {
                confirmedPasswordInput.type = 'password';
                icon.textContent = 'visibility_off'; 
            }
        }
    </script>
</body>
</html>