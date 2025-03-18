<?php
include 'header.php';
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
    <h1>Leave Us A Massage</h1>
    <div class="main_form">
        <form action="includes/insert_UserMassage.php" method="post" class="form_content">
        <label for="Email">E-mail:</label>
        <input type="email" id="Email" name="U_Mail" required>
        <label for="Subject">Subject:</label>
        <input type="text" id="Subject" name="U_Massage_Subject" maxlength="50" placeholder="Maximum Length of Subject is 50" required>
        <label for="Massage">Massage:</label>
        <textarea id="Massage" name="U_Massage" maxlength="500" placeholder="Maximum Length of Massage is 500" required></textarea>
        <button type="submit">Submit</button>
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