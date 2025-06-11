<?php
session_start();
include 'connect.php';

$upload_dir_file = '../phpfile/uploads/lesson/';
$upload_dir_thumbnail = '../phpfile/uploads/thumbnail/';

if (!is_dir($upload_dir_file)) {
    mkdir($upload_dir_file, 0755, true);
}
if (!is_dir($upload_dir_thumbnail)) {
    mkdir($upload_dir_thumbnail, 0755, true);
}

$thumbnail_name = '';
$file_name = '';
$error = false;
$error_messages = [];

if (isset($_FILES['thumbnail_image']) && $_FILES['thumbnail_image']['error'] == UPLOAD_ERR_OK) {
    $original_thumbnail = basename($_FILES['thumbnail_image']['name']);
    $original_thumbnail = str_replace(' ', '_', $original_thumbnail);
    $thumbnail_ext = pathinfo($original_thumbnail, PATHINFO_EXTENSION);
    
    $allowed_image_extensions = ['jpg', 'jpeg', 'png'];
    $image_extension = strtolower($thumbnail_ext);
    
    if (!in_array($image_extension, $allowed_image_extensions)) {
        $error = true;
        $error_messages[] = "Error: Only JPG, JPEG, and PNG images are allowed for thumbnails.";
    } else {
        $thumbnail_name = generateUniqueFilename($upload_dir_thumbnail, $original_thumbnail);
        $thumbnail_path = $upload_dir_thumbnail . $thumbnail_name;
        
        if (!move_uploaded_file($_FILES['thumbnail_image']['tmp_name'], $thumbnail_path)) {
            $error = true;
            $error_messages[] = "Failed to upload thumbnail";
        }
    }
} elseif (isset($_FILES['thumbnail_image']) && $_FILES['thumbnail_image']['error'] != UPLOAD_ERR_NO_FILE) {
    $error = true;
    $error_messages[] = "Thumbnail upload error: " . $_FILES['thumbnail_image']['error'];
}

if (isset($_FILES['lesson_file']) && $_FILES['lesson_file']['error'] == UPLOAD_ERR_OK) {
    $original_filename = basename($_FILES['lesson_file']['name']);
    $original_filename = str_replace(' ', '_', $original_filename);
    $file_ext = pathinfo($original_filename, PATHINFO_EXTENSION);
    
    $allowed_extensions = ['pdf', 'doc', 'docx'];
    $file_extension = strtolower($file_ext);
    
    if (!in_array($file_extension, $allowed_extensions)) {
        $error = true;
        $error_messages[] = "Error: Only PDF, DOC, and DOCX files are allowed for lessons.";
    } else {
        $file_name = generateUniqueFilename($upload_dir_file, $original_filename);
        $file_path = $upload_dir_file . $file_name;
        
        if (!move_uploaded_file($_FILES['lesson_file']['tmp_name'], $file_path)) {
            $error = true;
            $error_messages[] = "Failed to upload lesson file";
            if ($thumbnail_name && file_exists($upload_dir_thumbnail . $thumbnail_name)) {
                @unlink($upload_dir_thumbnail . $thumbnail_name);
            }
        }
    }
} elseif (isset($_FILES['lesson_file']) && $_FILES['lesson_file']['error'] != UPLOAD_ERR_NO_FILE) {
    $error = true;
    $error_messages[] = "Lesson file upload error: " . $_FILES['lesson_file']['error'];
    if ($thumbnail_name && file_exists($upload_dir_thumbnail . $thumbnail_name)) {
        @unlink($upload_dir_thumbnail . $thumbnail_name);
    }
}

if ($error) {
    if ($file_name && file_exists($upload_dir_file . $file_name)) {
        @unlink($upload_dir_file . $file_name);
    }
    if ($thumbnail_name && file_exists($upload_dir_thumbnail . $thumbnail_name)) {
        @unlink($upload_dir_thumbnail . $thumbnail_name);
    }
    
    $_SESSION['upload_errors'] = $error_messages;
    header("Location: ../teacher/upload_lesson.php?error=1");
    exit();
}

$title = mysqli_real_escape_string($connect, $_POST['title']);
$description = mysqli_real_escape_string($connect, $_POST['description']);
$category = mysqli_real_escape_string($connect, $_POST['category']);
$grading_criteria = isset($_POST['scoring_criteria']) ? mysqli_real_escape_string($connect, $_POST['scoring_criteria']) : '';

$sql_insert = "INSERT INTO lessons (title, description, category, grading_criteria, file_name, thumbnail_name) 
VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $connect->prepare($sql_insert);
$stmt->bind_param("ssssss", $title, $description, $category, $grading_criteria, $file_name, $thumbnail_name);

if ($stmt->execute()) {
    $lesson_id = $stmt->insert_id;
    $formatted_id = 'LL' . str_pad($lesson_id, 6, '0', STR_PAD_LEFT);
    mysqli_query($connect, "UPDATE lessons SET lesson_id = '$formatted_id' WHERE auto_id = $lesson_id");
    
    header("Location: ../teacher/lesson_management.php?success=1&tab=lessons");
    exit();
} else {
    if ($file_name && file_exists($upload_dir_file . $file_name)) {
        @unlink($upload_dir_file . $file_name);
    }
    if ($thumbnail_name && file_exists($upload_dir_thumbnail . $thumbnail_name)) {
        @unlink($upload_dir_thumbnail . $thumbnail_name);
    }
    
    die("Error: " . mysqli_error($connect));
}

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