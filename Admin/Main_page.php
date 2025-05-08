<?php
require_once '../includes/check_session_admin.php';
include '../includes/connect_DB.php';
include 'header_Admin.php';

$getMassageUnreadSql = "SELECT student_id FROM student_change_class WHERE status = 'pending';";
$getMassageUnreadStmt = $pdo->prepare($getMassageUnreadSql);
$getMassageUnreadStmt->execute();
$unreadMessage = $getMassageUnreadStmt->fetchAll();
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
    <div class="menu_container">
        <ul class="adminMenu">
        <li><a href="manageUser.php">Manage User</a></li>
        <li><a href="manageClass.php">Manage Class</a></li>
        <li><a href="viewClassPerformance.php">View Class</a></li>
        <li><a href="manageChangeClass.php">Change Class Requests</a></li>
        <li><a href="manageEvaluationreport.php">Evaluation Report</a></li>
        </ul>
    </div>
    <?php foreach ($unreadMessage as $message): ?>
    <div class="notification_container">
        <h2>Change Class Request</h2>
        <div>You have received a change class request send from <?php echo htmlspecialchars($message['student_id'])?>.</div>
    </div>
    <?php endforeach; ?>
</body>
</html>