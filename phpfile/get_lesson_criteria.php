<?php
include 'connect.php';

$lesson_id = $_GET['lesson_id'];

$query = "SELECT grading_criteria FROM lessons WHERE lesson_id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "s", $lesson_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    header('Content-Type: application/json');
    echo json_encode([
        'grading_criteria' => $row['grading_criteria']
    ]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Lesson not found']);
}
?>