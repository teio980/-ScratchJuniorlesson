<?php
session_start();
include 'connect.php';
include '../includes/connect_DB.php';

$teacher_id = $_SESSION['user_id'];
$submit_id = $_POST['submit_id'];
$student_id = $_POST['student_id'];
$lesson_id = $_POST['lesson_id'];
$score = $_POST['total_score'];
$feedback = $_POST['feedback'] ?? ''; 

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

$class_query = "SELECT class_id FROM student_submit WHERE submit_id = ?";
$class_stmt = mysqli_prepare($connect, $class_query);
mysqli_stmt_bind_param($class_stmt, "s", $submit_id);
mysqli_stmt_execute($class_stmt);
$class_result = mysqli_stmt_get_result($class_stmt);
$class_row = mysqli_fetch_assoc($class_result);
$class_id = $class_row['class_id'];

$update_query = "
    UPDATE student_submit 
    SET score = ?, 
        description = ?,
        upload_time = upload_time
    WHERE submit_id = ?
";
$stmt = mysqli_prepare($connect, $update_query);
mysqli_stmt_bind_param($stmt, "dss", $score, $feedback, $submit_id);

if (mysqli_stmt_execute($stmt)) {
    $average_query = "
        UPDATE student_class sc
        SET average_score = (
            SELECT AVG(score) 
            FROM student_submit 
            WHERE student_id = ? 
            AND class_id = ?
            AND score IS NOT NULL
        )
        WHERE student_id = ? 
        AND class_id = ?
    ";
    $avg_stmt = mysqli_prepare($connect, $average_query);
    mysqli_stmt_bind_param($avg_stmt, "ssss", $student_id, $class_id, $student_id, $class_id);
    mysqli_stmt_execute($avg_stmt);
    
    header("Location: ../teacher/view_submissions.php?success=1");
} else {
    die("Error: " . mysqli_error($connect));
}
?>