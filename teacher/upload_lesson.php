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
    <title>Upload Lesson</title>
</head>
<body>
    <h2>Submit a Lesson for ScratchJr</h2>
    <form action="../phpfile/upload_lesson_process.php" method="POST" enctype="multipart/form-data">
        <label for="title">Lesson Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Lesson Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>

        <label for="expire_date">Expire Date:</label>
        <input type="datetime-local" id="expire_date" name="expire_date" required><br><br>

        <label for="lesson_file">Upload Lesson File (Optional):</label>
        <input type="file" id="lesson_file" name="lesson_file"><br><br>
    
        <button type="submit" name="savebtn">Submit Lesson</button>
    </form>
    
    <button onclick="location.href='Main_page.php'">Back to Dashboard</button>
</body>
</html>