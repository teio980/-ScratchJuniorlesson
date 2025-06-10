<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';
include 'resheadteacher.php';

if (!isset($_SESSION['user_id'])) {
    echo "You need to be logged in as a teacher to upload materials.";
    exit;
}

$teacher_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id = $_POST['class_id'];
    $material_title = htmlspecialchars($_POST['material_title']);
    $material_description = htmlspecialchars($_POST['material_description']);
    $file = $_FILES['material_file'];

    $target_dir = "../phpfile/upload_teacher_material/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // 将文件名中的空格替换为下划线，避免路径问题
    $file_name = str_replace(' ', '_', basename($file["name"]));
    $target_file = $target_dir . $file_name;

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = array("pdf", "docx", "pptx");

    if (in_array($file_type, $allowed_types)) {
        $material_id = '';
        $sql = "SELECT COUNT(*) FROM teacher_materials";


        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $sql = "INSERT INTO teacher_materials (teacher_id, class_id, title, description, file_name)
                    VALUES ('$teacher_id', '$class_id', '$material_title', '$material_description', '$file_name')";

            if (mysqli_query($connect, $sql)) {
                echo "<script>alert('Material uploaded successfully.'); window.location.href='lesson_management.php?tab=materials';</script>";
            } else {
                echo "Database error: " . mysqli_error($connect);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Invalid file type. Only PDF, DOCX, and PPTX files are allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Teacher Material</title>
    <!-- 引入已有样式 -->
    <link rel="stylesheet" href="../cssfile/Teachermain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/upload_material.css">
</head>
<body>
    <h1 class="page-title">Upload New Material</h1>
    <div class="upload-container">
        <form method="POST" enctype="multipart/form-data" class="upload-form">
            <!-- Select Class -->
            <div class="form-group">
                <label for="class_id" class="required-field">Select Class:</label>
                <select name="class_id" id="class_id" required class="form-control">
                    <?php
                    $class_sql = "SELECT c.class_id, c.class_name FROM class c
                                JOIN teacher_class t ON c.class_id = t.class_id
                                WHERE t.teacher_id = '$teacher_id'";

                    $class_result = mysqli_query($connect, $class_sql);
                    if (!$class_result) {
                        die("Query failed: " . mysqli_error($connect));
                    }
                    if (mysqli_num_rows($class_result) === 0) {
                        echo "<option value=''>No classes assigned</option>";
                    } else {
                        echo "<option value=''>Please select a class</option>";
                        while ($class_row = mysqli_fetch_assoc($class_result)) {
                            echo "<option value='" . $class_row['class_id'] . "'>" . $class_row['class_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Material Title -->
            <div class="form-group">
                <label for="material_title" class="required-field">Title:</label>
                <input type="text" name="material_title" id="material_title" required class="form-control">
            </div>

            <!-- Material Description -->
            <div class="form-group">
                <label for="material_description" class="required-field">Material Description:</label>
                <textarea name="material_description" id="material_description" required class="form-control" rows="4"></textarea>
            </div>

            <!-- File Upload - Simplified version without JS -->
            <div class="form-group">
                <label for="material_file" class="required-field">Material File (PDF/Word/PPT):</label>
                <input type="file" name="material_file" id="material_file" required accept=".pdf,.docx,.pptx" class="form-control">
            </div>

            <!-- Form actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Upload Material</button>
                <a href="lesson_management.php?tab=materials" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>