<?php
session_start();
include 'connect.php';

// Generate lesson ID
$lesson_id = '';
$sql = "SELECT COUNT(*) FROM lessons";
if ($result = mysqli_query($connect, $sql)) {
    $row = mysqli_fetch_row($result);
    $lesson_Qty = (int)$row[0];
    $lesson_id = 'LL' . str_pad($lesson_Qty + 1, 6, '0', STR_PAD_LEFT);
    mysqli_free_result($result);
}

// Upload directory paths
$upload_dir_file = '../phpfile/uploads/lesson/';
$upload_dir_thumbnail = '../phpfile/uploads/thumbnail/';

// Create directories if they don't exist
if (!is_dir($upload_dir_file)) {
    mkdir($upload_dir_file, 0755, true);
}
if (!is_dir($upload_dir_thumbnail)) {
    mkdir($upload_dir_thumbnail, 0755, true);
}

// Process thumbnail upload
$thumbnail_name = '';
if (isset($_FILES['thumbnail_image']) && $_FILES['thumbnail_image']['error'] == UPLOAD_ERR_OK) {
    $original_thumbnail = basename($_FILES['thumbnail_image']['name']);
    $thumbnail_ext = pathinfo($original_thumbnail, PATHINFO_EXTENSION);
    $thumbnail_name = generateUniqueFilename($upload_dir_thumbnail, $original_thumbnail);
    $thumbnail_path = $upload_dir_thumbnail . $thumbnail_name;
    
    $allowed_image_extensions = ['jpg', 'jpeg', 'png'];
    $image_extension = strtolower($thumbnail_ext);
    
    if (!in_array($image_extension, $allowed_image_extensions)) {
        die("Error: Only JPG, JPEG, and PNG images are allowed.");
    }
    
    if (!move_uploaded_file($_FILES['thumbnail_image']['tmp_name'], $thumbnail_path)) {
        die("Failed to upload thumbnail");
    }
}

// Process lesson file upload
$file_name = '';
if (isset($_FILES['lesson_file']) && $_FILES['lesson_file']['error'] == UPLOAD_ERR_OK) {
    $original_filename = basename($_FILES['lesson_file']['name']);
    $file_ext = pathinfo($original_filename, PATHINFO_EXTENSION);
    $file_name = generateUniqueFilename($upload_dir_file, $original_filename);
    $file_path = $upload_dir_file . $file_name;
    
    $allowed_extensions = ['pdf', 'doc', 'docx'];
    $file_extension = strtolower($file_ext);
    
    if (!in_array($file_extension, $allowed_extensions)) {
        die("Error: Only PDF, DOC, and DOCX files are allowed.");
    }
    
    if (!move_uploaded_file($_FILES['lesson_file']['tmp_name'], $file_path)) {
        die("Failed to upload lesson file");
    }
}

// Get form data
$title = mysqli_real_escape_string($connect, $_POST['title']);
$description = mysqli_real_escape_string($connect, $_POST['description']);
$category = mysqli_real_escape_string($connect, $_POST['category']);
$grading_criteria = isset($_POST['scoring_criteria']) ? mysqli_real_escape_string($connect, $_POST['scoring_criteria']) : '';

// Insert into database
$sql_insert = "INSERT INTO lessons (lesson_id, title, description, category, grading_criteria, file_name, thumbnail_name) 
VALUES ('$lesson_id', '$title', '$description', '$category', '$grading_criteria', '$file_name', '$thumbnail_name')";

if (mysqli_query($connect, $sql_insert)) {
    header("Location: ../teacher/lesson_management.php?success=1&tab=lessons");
    exit();
} else {
    die("Error: " . mysqli_error($connect));
}

// 生成唯一文件名函数
function generateUniqueFilename($directory, $filename) {
    $counter = 1;
    $fileinfo = pathinfo($filename);
    $name = $fileinfo['filename'];
    $extension = isset($fileinfo['extension']) ? '.' . $fileinfo['extension'] : '';
    
    while (file_exists($directory . $filename)) {
        $filename = $name . '(' . $counter . ')' . $extension;
        $counter++;
    }
    
    return $filename;
}

mysqli_close($connect);
?>