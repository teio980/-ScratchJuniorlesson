<?php

include '../includes/connect_DB.php';
$student_id = $_SESSION['user_id'];

$getNotificationSql = "SELECT student_change_class_id AS ID,status, status_read FROM student_change_class WHERE student_id = :S_ID;";
$getNotificationStmt = $pdo->prepare($getNotificationSql);
$getNotificationStmt->bindParam(':S_ID',$student_id);
$getNotificationStmt->execute();
$notifications = $getNotificationStmt->fetchAll();

$hasUnread = false;
foreach ($notifications as $row) {
    if ($row['status_read'] == 'unread') {  
        $hasUnread = true;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/studentheader.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>LK Scratch Kids</title>
</head>
<body>
    <div class="tou">
    <div class="header-container">
        <div class="logo">
            <h1>LK Scratch Kids</h1>
        </div>
        <div class="side-menu">
            <ul class="side">
                <li><button style="all: unset; cursor : pointer;" ><span class="material-symbols-outlined" id="notification_icon" onclick="showNotifications()">notifications</span></button></li>
                <li><a href="Main_page.php" class="spe">Home</a></li>
                <li><a href="Personal_Profile.php" class="spe">Personal Profile</a></li>
                <li><a href="../includes/logout.php" class="spe">Sign Out</a></li>
            </ul>
        </div>
        <div class="notification_container" id="notification_container">
            <?php foreach ($notifications as $row): ?>
            <div class="notification_content">
                <h4>Change Class Request</h4>
                <div>Your change class request ID:<?php echo htmlspecialchars($row['ID'])?> has been <?php echo htmlspecialchars($row['status'])?></div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hasUnreadMessage = <?php echo $hasUnread ? 'true' : 'false'; ?>;
    const notificationIcon = document.getElementById('notification_icon');
    
    if (hasUnreadMessage) {
        notificationIcon.innerHTML = 'notifications_active';
    }

    notificationIcon.addEventListener('click', function() {
        notificationIcon.textContent = 'notifications';
    })

});

function showNotifications(){
    const notificationContent = document.getElementById('notification_container');
    if (notificationContent.style.display === "none") {
        notificationContent.style.display = "flex";
        fetch('../includes/updateReadStatus.php', {
        method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            console.log('Notifications marked as read');
        })
        .catch(error => {
            console.error('Error marking notifications as read:', error);
        });
    } else {
        notificationContent.style.display = "none";
    }
}   
</script>
</html>