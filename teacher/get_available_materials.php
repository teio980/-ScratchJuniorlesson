<?php
include '../includes/connect_DB.php';

$class_id = $_GET['class_id'];
$teacher_id = $_GET['teacher_id'];

$query = "SELECT tm.material_id, tm.title 
          FROM teacher_materials tm
          WHERE tm.teacher_id = ?
          AND (tm.class_id IS NULL OR tm.class_id = '' OR tm.class_id != ?)";
$stmt = $connect->prepare($query);
$stmt->bind_param("ss", $teacher_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();

$materials = [];
while ($row = $result->fetch_assoc()) {
    $materials[] = $row;
}

header('Content-Type: application/json');
echo json_encode($materials);
?>