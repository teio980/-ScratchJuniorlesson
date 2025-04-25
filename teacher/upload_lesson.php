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
    <title>Upload Lesson</title>
    
</head>
<body>
    <h2>Submit a Lesson for ScratchJr</h2>
    <form action="../phpfile/upload_lesson_process.php" method="POST" enctype="multipart/form-data">
        <label for="title">Lesson Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="thumbnail">Lesson Thumbnail (PNG/JPG):</label>
        <input type="file" id="thumbnail" name="thumbnail_image" accept="image/png, image/jpeg"><br><br>

        <div id="imagePreview" style="width: 100px; height: 100px; overflow: hidden; border: 1px solid #ccc;">
            <img id="thumbnailPreview" src="" alt="Image preview" style="width: 100%; height: auto;">
        </div><br><br>

        <label for="description">Lesson Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>

        <label for="lesson_file">Upload Lesson File (PDF/Word):</label>
        <input type="file" id="lesson_file" name="lesson_file" accept=".pdf, .docx"><br><br>
        
        
    
        <button type="submit" name="savebtn">Submit Lesson</button>
    </form>
    
    <button onclick="location.href='Main_page.php'">Back to Dashboard</button>
</body>
</html>
