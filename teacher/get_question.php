<?php
include '../phpfile/connect.php';
include '../includes/connect_DB.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM questions WHERE id = '$id'";
    $result = mysqli_query($connect, $query);
    $question = mysqli_fetch_assoc($result);
    echo json_encode($question);
}
?>