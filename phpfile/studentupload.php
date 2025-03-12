<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file_name = $_FILES["file"]["name"];
    $file_tmp = $_FILES["file"]["tmp_name"];
    $upload_dir = "uploads/";

    // Create upload directory if not exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $target_file = $upload_dir . basename($file_name);

    // Move file to upload directory
    if (move_uploaded_file($file_tmp, $target_file)) {
        // Save to database
        $stmt = $connect->prepare("INSERT INTO projects (filename, filepath) VALUES (?, ?)");
        $stmt->bind_param("ss", $file_name, $target_file);
        $stmt->execute();
        $stmt->close();

        echo "File uploaded successfully!";
    } else {
        echo "Error uploading file.";
    }
}


$connect->close();
?>
