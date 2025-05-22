<?php
session_start();
include '../phpfile/connect.php';

$submit_id = $_POST['submit_id'];
$student_id = $_POST['student_id'];
$code_quality = $_POST['code_quality'];
$problem_solving = $_POST['problem_solving'];
$creativity = $_POST['creativity'];
$presentation = $_POST['presentation'];

$total_score = $code_quality + $problem_solving + $creativity + $presentation;

$check_query = "SELECT * FROM ratings WHERE submit_id = '$submit_id'";
$check_result = mysqli_query($connect, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    $update_query = "UPDATE ratings SET rating = '$total_score', rated_at = NOW() WHERE submit_id = '$submit_id'";
    mysqli_query($connect, $update_query);
} else {
    $insert_query = "INSERT INTO ratings (submit_id, student_id, rating) VALUES ('$submit_id', '$student_id', '$total_score')";
    mysqli_query($connect, $insert_query);
}

header("Location: view_submissions.php"); 
exit;
?>
