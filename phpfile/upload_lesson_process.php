<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['savebtn'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $expire_date = $_POST['expire_date'];
    
    $filepath = null;
    if (!empty($_FILES['lesson_file']['name'])) {
        $target_dir = "../uploads/lesson_files/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $filename = basename($_FILES['lesson_file']['name']);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($_FILES['lesson_file']['tmp_name'], $target_file)) {
            $filepath = $target_file;
        }
    }
    
    $stmt = $connect->prepare("INSERT INTO lessons (title, description, expire_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $expire_date);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Lesson submitted successfully!";
    } else {
        $_SESSION['error'] = "Error submitting lesson: " . $stmt->error;
    }
    
    $stmt->close();
    header("Location: ../teacher/upload_lesson.php");
    exit();
}
?>