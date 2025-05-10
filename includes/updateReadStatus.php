<?php
session_start();
include '../includes/connect_DB.php';

$student_id = $_SESSION['user_id'];

$updateSql = "UPDATE student_change_class SET status_read = 'read' WHERE student_id = :S_ID AND status_read = 'unread';";
$updateStmt = $pdo->prepare($updateSql);
$updateStmt->bindParam(':S_ID', $student_id);
$updateStmt->execute();

echo json_encode(['success' => true]);
?>
