<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"]) && isset($_POST["lesson_id"])) {
    $lesson_id = $_POST["lesson_id"]; // Get lesson_id from POST
    $file_name = $_FILES["file"]["name"];
    $file_tmp = $_FILES["file"]["tmp_name"];
    $upload_dir = "uploads/";

    // Create the uploads directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $target_file = $upload_dir . basename($file_name);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file_tmp, $target_file)) {
        // Insert into database, including lesson_id
        $stmt = $connect->prepare("INSERT INTO projects (lesson_id, filename, filepath) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $lesson_id, $file_name, $target_file);
        $stmt->execute();
        $stmt->close();

        echo "File uploaded successfully!";
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "Invalid request. Lesson ID or file is missing.";
}

$connect->close();
?>
