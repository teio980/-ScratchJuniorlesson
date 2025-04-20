<?php
include 'connect.php';

if (isset($_POST["lesson_id"]) && isset($_POST["savebtn"])) {
    $lesson_id = $_POST["lesson_id"]; 
    $file_name = $_FILES["file"]["name"];
    $file_tmp = $_FILES["file"]["tmp_name"];
    $upload_dir = "../phpfile/uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $target_file = $upload_dir . basename($file_name);


    if (move_uploaded_file($file_tmp, $target_file)) { 
        $stmt = $connect->prepare("INSERT INTO projects (lesson_id, filename, filepath) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $lesson_id, $file_name, $target_file);
        $stmt->execute();
        $stmt->close();

        echo "File uploaded successfully!";
    } else {
        echo "Error uploading file.";
    }
}
$connect->close();
?>
