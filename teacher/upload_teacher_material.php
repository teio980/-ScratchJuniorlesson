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

    $file_name = str_replace(' ', '_', basename($file["name"]));
    $target_file = $target_dir . $file_name;

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = array("pdf", "docx", "pptx");

    if (in_array($file_type, $allowed_types)) {
        $material_id = '';
        $sql = "SELECT COUNT(*) FROM teacher_materials";
        if ($result = mysqli_query($connect, $sql)) {
            $row = mysqli_fetch_row($result);
            $material_Qty = (int)$row[0];
            $material_id = 'M' . str_pad($material_Qty + 1, 6, '0', STR_PAD_LEFT);
            mysqli_free_result($result);
        }

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $sql = "INSERT INTO teacher_materials (material_id, teacher_id, class_id, title, description, file_name) 
                    VALUES ('$material_id', '$teacher_id', '$class_id', '$material_title', '$material_description', '$file_name')";

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
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/upload_material.css">
    <title>Upload Teacher Material</title>
</head>
<body>
    <div class="container" style="width: 60%; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h1 style="text-align: center;">Upload Teacher Material</h1>
        <form method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column;">
            <div style="margin-bottom: 20px;">
                <label for="class_id" style="display: block; font-weight: bold; margin-bottom: 8px;">Select Class:</label>
                <select name="class_id" id="class_id" required style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px;">
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

            <div style="margin-bottom: 20px;">
                <label for="material_title" style="display: block; font-weight: bold; margin-bottom: 8px;">Material Title:</label>
                <input type="text" name="material_title" id="material_title" required style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label for="material_description" style="display: block; font-weight: bold; margin-bottom: 8px;">Material Description:</label>
                <textarea name="material_description" id="material_description" required style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="material_file" style="display: block; font-weight: bold; margin-bottom: 8px;">Upload Material (PDF, DOCX, PPTX only):</label>
                <input type="file" name="material_file" id="material_file" required style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px;" accept=".pdf,.docx,.pptx">

            </div>

            <button type="submit" style="padding: 10px 20px; font-size: 16px; background-color:rgb(142, 60, 181); color: white; border: none; border-radius: 4px; cursor: pointer; width: 100%;">Upload Material</button>
        </form>
    </div>
</body>
</html>
