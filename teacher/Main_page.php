<?php
session_start();
include '../phpfile/connect.php';
include '../resheadAfterLogin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <title>Teacher</title>
</head>
<body>
    <h1>Welcome, Teacher</h1>

    <div class="teacher-options">
        <button onclick="location.href='lesson_management.php'">Lesson Management</button>
        <button onclick="location.href='view_submissions.php'">View Student Submissions</button>
        <button onclick="location.href='quizupload.php'">Upload Quiz Questions</button>
        <button onclick="location.href='availablework.php'">Work for student</button>
    </div>

    <script src="../javascriptfile/teacher_main.js"></script>
</body>
</html>