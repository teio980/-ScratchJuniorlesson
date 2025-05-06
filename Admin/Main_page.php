<?php
require_once '../includes/check_session_admin.php';
include 'header_Admin.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headerAdmin.css">
    <link rel="stylesheet" href="../cssfile/adminMenu.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Admin</title>
</head>
<body>
    <h1>Admin page</h1>
    <div class="menu_container">
    <ul class="adminMenu">
    <li><a href="manageUser.php">Manage User</a></li>
    <li><a href="manageClass.php">Manage Class</a></li>
    <li><a href="manageEvaluationreport.php">Evaluation Report</a></li>
    </ul>
    <span class="material-symbols-outlined" id="menu_icon">menu</span>
    </div>
</body>
<script>
    
document.getElementById('menu_icon').addEventListener('click', function() {
    const menu = document.querySelector('.adminMenu');
    menu.classList.toggle('active');
});

</script>
</html>