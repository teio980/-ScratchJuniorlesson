<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $feedback = $_POST['feedback'];
    
    $check_column = $connect->query("SHOW COLUMNS FROM projects LIKE 'feedback'");
    if ($check_column->num_rows == 0) {
        $connect->query("ALTER TABLE projects ADD COLUMN feedback TEXT");
    }
    
    $stmt = $connect->prepare("UPDATE projects SET feedback = ? WHERE id = ?");
    $stmt->bind_param("si", $feedback, $project_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Feedback submitted successfully!";
    } else {
        $_SESSION['error'] = "Error submitting feedback: " . $stmt->error;
    }
    
    $stmt->close();
    header("Location: ../teacher/view_submissions.php");
    exit();
}
?>