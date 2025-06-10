<?php
session_start();
require_once '../phpfile/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $availability_id = $_POST['availability_id'] ?? '';
    $message = $_POST['message'] ?? '';
    $teacher_id = $_SESSION['user_id'] ?? '';
    
    // Verify the user is a teacher
    $sql = "SELECT teacher_id FROM teacher WHERE teacher_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die("Only teachers can post comments");
    }
    
    $sql = "SELECT COUNT(*) FROM content_comments";
    $result = $connect->query($sql);
    $row = $result->fetch_row();
    $comment_id = 'CMT' . str_pad($row[0] + 1, 7, '0', STR_PAD_LEFT);
    
    $insert_sql = "INSERT INTO content_comments 
                  (comment_id, availability_id, teacher_id, message)
                  VALUES (?, ?, ?, ?)";
    $stmt = $connect->prepare($insert_sql);
    $stmt->bind_param("ssss", 
        $comment_id, 
        $availability_id,
        $teacher_id,
        $message
    );
    
    if ($stmt->execute()) {
        // Return to the referring page
        $referer = $_SERVER['HTTP_REFERER'] ?? 'assigned_lessons.php';
        header("Location: $referer");
    } else {
        die("Failed to post comment: " . $stmt->error);
    }
} else {
    die("Invalid request");
}
?>