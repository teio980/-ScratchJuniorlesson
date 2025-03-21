<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssfile/login.css">
    <link rel="stylesheet" href="cssfile/header.css">
    <title>Document</title>
</head>
<body>
    <div class="main">
        <div class="left"><img src="img_logo/knowledge-4171793_1280.jpg" alt=""></div>
        <div class="right" >
            <div class="login">
                <form action="includes/loginFunction.php" method="POST">
                    <label for="U_Username">Username:</label>
                    <input type="text"  name="U_Username" id="U_Username" placeholder="Username" required>
                    <label for="U_Password">Password:</label>
                    <input type="password" name="U_Password" id="U_Password" placeholder="Password" required>
                    <div class="link">
                    <a href="forgotPassword.php">Forgot Password?</a>
                    <a href="register.php">Register</a>
                    </div>
                    <button type="submit">login</button>
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
    </script>
</body>
</html>