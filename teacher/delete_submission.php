<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';

function deleteSubmission($connect, $submit_id) {
    // 获取学生和班级信息
    $info_query = "SELECT student_id, class_id FROM student_submit WHERE submit_id = ?";
    $stmt = mysqli_prepare($connect, $info_query);
    mysqli_stmt_bind_param($stmt, "s", $submit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $info = mysqli_fetch_assoc($result);
    
    if (!$info) {
        return false;
    }
    
    // 删除记录
    $delete_query = "DELETE FROM student_submit WHERE submit_id = ?";
    $stmt = mysqli_prepare($connect, $delete_query);
    mysqli_stmt_bind_param($stmt, "s", $submit_id);
    $delete_result = mysqli_stmt_execute($stmt);
    
    if (!$delete_result) {
        return false;
    }
    
    // 更新学生平均分
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
    mysqli_stmt_bind_param($avg_stmt, "ssss", $info['student_id'], $info['class_id'], 
                      $info['student_id'], $info['class_id']);
    mysqli_stmt_execute($avg_stmt);
    
    // 更新班级平均分
    $class_avg_query = "
        UPDATE class c
        SET class_average = (
            SELECT AVG(average_score)
            FROM student_class
            WHERE class_id = ?
            AND average_score IS NOT NULL
        )
        WHERE class_id = ?
    ";
    $class_stmt = mysqli_prepare($connect, $class_avg_query);
    mysqli_stmt_bind_param($class_stmt, "ss", $info['class_id'], $info['class_id']);
    mysqli_stmt_execute($class_stmt);
    
    return true;
}

// 处理删除请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_id'])) {
    $submit_id = $_POST['submit_id'];
    $teacher_id = $_SESSION['user_id'];
    
    // 验证老师是否有权限删除此提交
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
    
    if (mysqli_num_rows($check_result) > 0 && deleteSubmission($connect, $submit_id)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Unauthorized or failed to delete']);
    }
    exit;
}
?>