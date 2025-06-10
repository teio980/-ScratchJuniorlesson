<?php
session_start();
require_once '../phpfile/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $availability_id = $_POST['availability_id'] ?? '';
    $message = $_POST['message'] ?? '';
    $user_id = $_SESSION['user_id'] ?? '';
    
    $user_type = '';
    $sql = "SELECT identity FROM (
            SELECT teacher_id AS id, identity FROM teacher WHERE teacher_id = ?
            UNION
            SELECT student_id AS id, identity FROM student WHERE student_id = ?
            UNION
            SELECT admin_id AS id, identity FROM admin WHERE admin_id = ?
        ) AS users LIMIT 1";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sss", $user_id, $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die("Invalid user");
    }
    
    $user = $result->fetch_assoc();
    $sender_type = ($user['identity'] === 'teacher') ? 'teacher' : 'student';
    
    $sql = "SELECT COUNT(*) FROM content_comments";
    $result = $connect->query($sql);
    $row = $result->fetch_row();
    $comment_id = 'CMT' . str_pad($row[0] + 1, 7, '0', STR_PAD_LEFT);
    
    $insert_sql = "INSERT INTO content_comments 
                (availability_id, sender_id, sender_type, message)
                VALUES (?, ?, ?, ?)";
    $stmt = $connect->prepare($insert_sql);
    $stmt->bind_param("ssss", 
        $availability_id,
        $user_id,
        $sender_type,
        $message
    );
        
    if ($stmt->execute()) {
        // Return to the previous page
        $referer = $_SERVER['HTTP_REFERER'] ?? 'assigned_lessons.php';
        header("Location: $referer");
    } else {
        die("Failed to post comment: " . $stmt->error);
    }
} else {
    die("Invalid request");
}
?>