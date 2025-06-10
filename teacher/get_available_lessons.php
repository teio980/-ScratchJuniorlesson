<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';

header('Content-Type: application/json');

if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];
    
    $query = "SELECT lesson_id FROM class_work WHERE class_id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("s", $class_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $assignedLessons = [];
    while ($row = $result->fetch_assoc()) {
        $assignedLessons[] = $row['lesson_id'];
    }
    
    echo json_encode($assignedLessons);
} else {
    echo json_encode([]);
}
?>