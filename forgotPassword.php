<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssfile/forgotPassword.css">
    <link rel="stylesheet" href="cssfile/header.css">
    <title>Document</title>
</head>
<body>
    <h1>Reset Your Password</h1>
    <p>An email will be send to you to reset your password.</p>
    <div class="mainform">
        <form action="includes/forgotPassword.php" method="POST" class="form_content">
            <input type="email" name="email" placeholder="Enter your email address...">
            <button type="submit" name="reset_password_button">Submit</button>
        </form>
    </div>
</body>
<script>
    function showSidebar() {
        document.querySelector('.side').classList.add('show');
    }

    function hideSidebar() {
        document.querySelector('.side').classList.remove('show');
    }
</script>
</html>