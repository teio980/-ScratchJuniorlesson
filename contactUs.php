<?php
include 'reshead.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssfile/contactUs.css">
    <link rel="stylesheet" href="cssfile/header.css">
    <title>Contact Us</title>
</head>
<body>
    <div class="main_form">
        <form action="includes/insert_UserMassage.php" method="post" class="form_content">
        <h1>Leave Us A Massage</h1>
        <label for="phoneNumber">Phones No. (Example: 012-3456789):</label>
        <input type="tel" name="phoneNumber" id="phoneNumber" placeholder="xxx-xxxxxxxx" pattern="[0-9]{3}-[0-9]{7,8}" required>
        <label for="Massage">Massage:</label>
        <textarea id="Massage" name="U_Massage" maxlength="500" placeholder="content" required></textarea>
        <button type="submit">Send Massage</button>
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