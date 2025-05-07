<?php
session_start();
include '../phpfile/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $material_id = uniqid('material_'); 
    $title = $_POST['title'];
    $description = $_POST['description'];

    $file = $_FILES['file'];
    $file_name = '';

    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($file['name']);
        $upload_dir = '../phpfile/uploads/teacher_material/';
        $upload_path = $upload_dir . $file_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $sql = "INSERT INTO teacher_materials (material_id, title, description, file_name) VALUES (?, ?, ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->bind_param("ssss", $material_id, $title, $description, $file_name);

            if ($stmt->execute()) {
                echo "<script>alert('Upload successful!'); location.href='teacher_material_list.php';</script>";
                exit;
            } else {
                echo "Database insert error: " . $stmt->error;
            }
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "File upload error: " . $file['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Teacher Material</title>
    <link rel="stylesheet" href="../cssfile/Tmain.css">
</head>
<body>
    <div class="container">
        <h1>Upload Teacher Material</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="file">Select File (.pdf, .docx):</label>
                <input type="file" name="file" id="file" accept=".pdf,.docx" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="upload-btn">Upload</button>
                <button type="button" onclick="location.href='Main_page.php'" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
