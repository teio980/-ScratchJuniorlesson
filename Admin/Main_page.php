<?php
session_start();
include '../resheadAfterLogin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/reshead.css">
    <title>Admin</title>
</head>
<body>
    <h1>Admin page</h1>
    <a href="addUser.php">Register User Account</a>
    <a href="manageUser.php">Manage User</a>
    <a href="evaluationreport.php">Evaluation Report</a>
</body>
</html>