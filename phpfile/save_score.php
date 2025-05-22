<?php
session_start();
include 'connect.php';

$teacher_id = $_SESSION['user_id'];
$submit_id = $_POST['submit_id'];
$student_id = $_POST['student_id'];
$lesson_id = $_POST['lesson_id'];
$score = $_POST['total_score'];

// 验证老师是否有权限批改这个作业
$check_query = "
    SELECT 1 
    FROM student_submit ss
    JOIN student_class sc ON ss.student_id = sc.student_id
    JOIN teacher_class tc ON sc.class_id = tc.class_id
    WHERE ss.submit_id = ? 
    AND tc.teacher_id = ?
";
$stmt = mysqli_prepare($connect, $check_query);
mysqli_stmt_bind_param($stmt, "ss", $submit_id, $teacher_id);
mysqli_stmt_execute($stmt);
$check_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($check_result) == 0) {
    die("You are not authorized to grade this submission.");
}

// 更新分数
$update_query = "
    UPDATE student_submit 
    SET score = ?, 
        upload_time = upload_time
    WHERE submit_id = ?
";
$stmt = mysqli_prepare($connect, $update_query);
mysqli_stmt_bind_param($stmt, "ds", $score, $submit_id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../teacher/view_submissions.php?success=1");
} else {
    die("Error: " . mysqli_error($connect));
}
?>