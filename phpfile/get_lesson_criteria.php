<?php
ob_clean();
include 'connect.php';

$lesson_id = $_GET['lesson_id'] ?? null;
$submit_id = $_GET['submit_id'] ?? null;
$response = [];

if ($lesson_id) {
    $query = "SELECT grading_criteria FROM lessons WHERE lesson_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "s", $lesson_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $response['grading_criteria'] = $row['grading_criteria'];
    } else {
        http_response_code(404);
        die(json_encode(['error' => 'Lesson not found']));
    }
}

if ($submit_id) {
    $query = "SELECT description AS feedback, score FROM student_submit WHERE submit_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "s", $submit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $response['feedback'] = $row['feedback'];
        $response['existing_score'] = $row['score'];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>