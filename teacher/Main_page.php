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
    <link rel="stylesheet" href="../cssfile/reshead.css">
    <title>Teacher</title>
</head>
<body>
    <h1>Teacher page</h1>

    <div class="teacher-options">
        <button onclick="location.href='upload_lesson.php'">Upload New Lesson</button>
        <button onclick="location.href='view_submissions.php'">View Student Submissions</button>
        <button onclick="location.href='quizupload.php'">Upload Quiz Questions</button>
    </div>
</body>
</html>