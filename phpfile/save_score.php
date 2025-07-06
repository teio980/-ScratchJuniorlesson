<?php
session_start();
include 'connect.php';
include '../includes/connect_DB.php';

header('Content-Type: application/json');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$teacher_id = $_SESSION['user_id'] ?? null;
if (!$teacher_id) {
    die(json_encode(['success' => false, 'error' => 'Unauthorized']));
}

$submit_id = $_POST['submit_id'] ?? null;
$student_id = $_POST['student_id'] ?? null;
$lesson_id = $_POST['lesson_id'] ?? null;
$score = $_POST['total_score'] ?? null;
$feedback = $_POST['feedback'] ?? '';

if (!$submit_id || !$student_id || !$lesson_id || $score === null) {
    die(json_encode(['success' => false, 'error' => 'Missing parameters']));
}

$response = ['success' => false];
$class_id = null;

$check_stmt = $class_stmt = $update_stmt = $avg_stmt = $class_avg_stmt = null;

try {
    $connect->begin_transaction();

    $check_query = "SELECT 1 FROM student_submit ss
                   JOIN student_class sc ON ss.student_id = sc.student_id
                   JOIN teacher_class tc ON sc.class_id = tc.class_id
                   WHERE ss.submit_id = ? AND tc.teacher_id = ?";
    $check_stmt = $connect->prepare($check_query);
    $check_stmt->bind_param("ss", $submit_id, $teacher_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
        throw new Exception("You are not authorized to grade this submission.");
    }
    mysqli_free_result($check_result);

    $class_query = "SELECT class_id FROM student_submit WHERE submit_id = ?";
    $class_stmt = $connect->prepare($class_query);
    $class_stmt->bind_param("s", $submit_id);
    $class_stmt->execute();
    $class_result = $class_stmt->get_result();
    $class_row = $class_result->fetch_assoc();
    $class_id = $class_row['class_id'];
    mysqli_free_result($class_result);

    $update_query = "UPDATE student_submit 
                    SET score = ?, description = ?, upload_time = NOW()
                    WHERE submit_id = ?";
    $update_stmt = $connect->prepare($update_query);
    $update_stmt->bind_param("dss", $score, $feedback, $submit_id);
    $update_stmt->execute();

    $student_avg_query = "UPDATE student_class sc
                        SET average_score = (
                            SELECT AVG(score) 
                            FROM student_submit 
                            WHERE student_id = ? AND class_id = ?
                            AND score IS NOT NULL
                        )
                        WHERE student_id = ? AND class_id = ?";
    $avg_stmt = $connect->prepare($student_avg_query);
    $avg_stmt->bind_param("ssss", $student_id, $class_id, $student_id, $class_id);
    $avg_stmt->execute();

    $class_avg_query = "UPDATE class c
                        SET class_average = (
                            SELECT 
                                CASE 
                                    WHEN (SELECT COUNT(*) FROM student_class WHERE class_id = ?) = 0 THEN 0
                                    ELSE COALESCE(SUM(sc.average_score), 0) / 
                                        (SELECT COUNT(*) FROM student_class WHERE class_id = ?)
                                END
                            FROM student_class sc
                            WHERE sc.class_id = ?
                            AND sc.average_score IS NOT NULL
                        )
                        WHERE c.class_id = ?";
    $class_avg_stmt = $connect->prepare($class_avg_query);
    $class_avg_stmt->bind_param("ssss", $class_id, $class_id, $class_id, $class_id);
    $class_avg_stmt->execute();

    $connect->commit();

    $updated_data_query = "SELECT ss.score, sc.average_score, c.class_average 
                          FROM student_submit ss
                          JOIN student_class sc ON ss.student_id = sc.student_id
                          JOIN class c ON sc.class_id = c.class_id
                          WHERE ss.submit_id = ?";
    $updated_stmt = $connect->prepare($updated_data_query);
    $updated_stmt->bind_param("s", $submit_id);
    $updated_stmt->execute();
    $updated_result = $updated_stmt->get_result();
    $updated_data = $updated_result->fetch_assoc();
    
    $response = [
        'success' => true,
        'message' => 'Score updated successfully',
        'newAverage' => $score,
        'studentAverage' => $updated_data['average_score'],
        'classAverage' => $updated_data['class_average'],
        'submit_id' => $submit_id,
        'student_id' => $student_id,
        'timestamp' => date('Y-m-d H:i:s')
    ];

} catch (Exception $e) {
    if ($connect) {
        $connect->rollback();
    }
    
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
} finally {
    $statements = [$check_stmt, $class_stmt, $update_stmt, $avg_stmt, $class_avg_stmt];
    foreach ($statements as $stmt) {
        if ($stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
    }
    
    echo json_encode($response);
    exit;
}
?>