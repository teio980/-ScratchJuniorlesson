<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['savebtn'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (!empty($_FILES['lesson_file']['name'])) {
        $target_dir = __DIR__ . "/../phpfile/uploads_teacher/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if ($_FILES['lesson_file']['error'] === UPLOAD_ERR_OK) {
            $lesson_file_name = basename($_FILES['lesson_file']['name']);
            $target_file = $target_dir . $lesson_file_name;

            if (move_uploaded_file($_FILES['lesson_file']['tmp_name'], $target_file)) {
                $lesson_file_path = "/phpfile/uploads/" . $lesson_file_name;
            } else {
                $_SESSION['error'] = "Failed to move uploaded lesson file!";
                header("Location: ../teacher/upload_lesson.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Lesson file upload error: " . $_FILES['lesson_file']['error'];
            header("Location: ../teacher/upload_lesson.php");
            exit();
        }
    }

    if (!empty($_FILES['thumbnail_image']['name'])) {
        $target_dir = __DIR__ . "/../phpfile/uploads/thumbnail/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if ($_FILES['thumbnail_image']['error'] === UPLOAD_ERR_OK) {
            $thumbnail_name = basename($_FILES['thumbnail_image']['name']);
            $target_file = $target_dir . $thumbnail_name;

            if (move_uploaded_file($_FILES['thumbnail_image']['tmp_name'], $target_file)) {
                $thumbnail_path = "/phpfile/uploads/thumbnail/" . $thumbnail_name;
            } else {
                $_SESSION['error'] = "Failed to move uploaded thumbnail!";
                header("Location: ../teacher/upload_lesson.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Thumbnail upload error: " . $_FILES['thumbnail_image']['error'];
            header("Location: ../teacher/upload_lesson.php");
            exit();
        }
    }

    $sql_T_checkQty = "SELECT COUNT(*) FROM lessons";
    $result_T_checkQty = mysqli_query($connect, $sql_T_checkQty);
    
    if ($result_T_checkQty) {
        $row = mysqli_fetch_row($result_T_checkQty);
        $lesson_Qty = $row[0];
        $lesson_id = 'LL' . str_pad($lesson_Qty + 1, 6, '0', STR_PAD_LEFT);
    }

    $stmt = $connect->prepare("INSERT INTO lessons 
        (lesson_id,title, description, lesson_file_name, file_path, thumbnail_name, thumbnail_path)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss",$lesson_id, $title, $description, $lesson_file_name, $lesson_file_path, $thumbnail_name, $thumbnail_path);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Lesson uploaded successfully!";
    } else {
        $_SESSION['error'] = "Error submitting lesson: " . $stmt->error;
    }

    $stmt->close();
    header("Location: ../teacher/upload_lesson.php");
    exit();
}
?>
