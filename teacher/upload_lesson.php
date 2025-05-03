<?php
session_start();
include '../phpfile/connect.php';
include '../resheadAfterLogin.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$typePrefix = '';

switch ($type) {
    case 'assignment':
        $typePrefix = 'Assignment - ';
        break;
    case 'material':
        $typePrefix = 'Material - ';
        break;
    case 'project':
        $typePrefix = 'Project - ';
        break;
    case 'exercise':
        $typePrefix = 'Exercise - ';
        break;
    default:
        $typePrefix = '';
        break;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $fullTitle = $typePrefix . $title;

    $description = $_POST['description'];
    $lesson_file = $_FILES['lesson_file']['name'];
    $thumbnail = $_FILES['thumbnail']['name'];


    $sql = "INSERT INTO lessons (title, description, lesson_file_name, thumbnail_name, create_time) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ssss", $fullTitle, $description, $lesson_file, $thumbnail);
    $stmt->execute();

    header("Location: lesson_management.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <title>Upload Lesson</title>
</head>
<body>
    <div class="container">
        <h1>Upload New Lesson</h1>
        
        <form action="../phpfile/upload_lesson_process.php" method="POST" enctype="multipart/form-data" class="lesson-form">
            <div class="form-group">
                <label for="title">Lesson Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="thumbnail">Lesson Thumbnail (PNG/JPG):</label>
                <input type="file" id="thumbnail" name="thumbnail_image" accept="image/png, image/jpeg">
            </div>
            
            <div class="form-group">
                <label for="expire_date">Expire Date:</label>
                <input type="date" id="expire_date" name="expire_date" required>
            </div>
            
            <div class="form-group">
                <label for="description">Lesson Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="lesson_file">Upload Lesson File (PDF/Word):</label>
                <input type="file" id="lesson_file" name="lesson_file" accept=".pdf, .docx">
            </div>
            
            <div class="form-actions">
                <button type="submit" name="savebtn" class="submit-btn">Submit Lesson</button>
                <button type="button" onclick="location.href='lesson_management.php'" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("expire_date").setAttribute("min", new Date().toISOString().split("T")[0]);
    </script>
</body>
</html>