<?php
session_start();
include 'connect.php';  

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['savebtn'])) {
    header("Location: ../teacher/upload_lesson.php");
    exit();
}

define("UPLOADS_DIR", __DIR__ . "/../phpfile/uploads_teacher/");
define("THUMBNAIL_DIR", __DIR__ . "/../phpfile/uploads/thumbnail/");
define("BASE_UPLOAD_PATH", "/phpfile/uploads/");

$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

if (empty($title)) {
    $_SESSION['error'] = "Title is required!";
    header("Location: ../teacher/upload_lesson.php");
    exit();
}

$lesson_file_name = null;
$lesson_file_path = null;
$thumbnail_name = null;
$thumbnail_path = null;

if (!empty($_FILES['lesson_file']['name'])) {
    if (!is_dir(UPLOADS_DIR)) {
        mkdir(UPLOADS_DIR, 0755, true);
    }

    if ($_FILES['lesson_file']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = sprintf("Lesson file upload error: %s", $_FILES['lesson_file']['error']);
        header("Location: ../teacher/upload_lesson.php");
        exit();
    }

    $lesson_file_name = basename($_FILES['lesson_file']['name']);
    $target_file = UPLOADS_DIR . $lesson_file_name;

    if (!move_uploaded_file($_FILES['lesson_file']['tmp_name'], $target_file)) {
        $_SESSION['error'] = "Failed to move uploaded lesson file!";
        header("Location: ../teacher/upload_lesson.php");
        exit();
    }
    
    $lesson_file_path = BASE_UPLOAD_PATH . $lesson_file_name;
}

if (!empty($_FILES['thumbnail_image']['name'])) {
    if (!is_dir(THUMBNAIL_DIR)) {
        mkdir(THUMBNAIL_DIR, 0755, true);
    }

    if ($_FILES['thumbnail_image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = sprintf("Thumbnail upload error: %s", $_FILES['thumbnail_image']['error']);
        header("Location: ../teacher/upload_lesson.php");
        exit();
    }

    $thumbnail_name = basename($_FILES['thumbnail_image']['name']);
    $target_file = THUMBNAIL_DIR . $thumbnail_name;

    if (!move_uploaded_file($_FILES['thumbnail_image']['tmp_name'], $target_file)) {
        $_SESSION['error'] = "Failed to move uploaded thumbnail!";
        header("Location: ../teacher/upload_lesson.php");
        exit();
    }
    
    $thumbnail_path = BASE_UPLOAD_PATH . "thumbnail/" . $thumbnail_name;
}

$lesson_id = '';
$sql = "SELECT COUNT(*) FROM lessons";
if ($result = mysqli_query($connect, $sql)) {
    $row = mysqli_fetch_row($result);
    $lesson_Qty = (int)$row[0];
    $lesson_id = 'LL' . str_pad($lesson_Qty + 1, 6, '0', STR_PAD_LEFT);
    mysqli_free_result($result);
}

$sql_insert = "INSERT INTO lessons 
    (lesson_id, title, description, lesson_file_name, file_path, thumbnail_name, thumbnail_path) 
    VALUES 
    ('$lesson_id', '$title', '$description', '$lesson_file_name', '$lesson_file_path', '$thumbnail_name', '$thumbnail_path')";

if (mysqli_query($connect, $sql_insert)) {
    $_SESSION['message'] = "Lesson uploaded successfully!";
} else {
    $_SESSION['error'] = "Error submitting lesson: " . mysqli_error($connect);
}

header("Location: ../teacher/upload_lesson.php");
exit();
?>
