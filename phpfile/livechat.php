<?php
include '../phpfile/connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = mysqli_real_escape_string($connect, $_POST['user_id']);
    $message = mysqli_real_escape_string($connect, $_POST['message']);

    $sql = "INSERT INTO student_livechat (student_id, chat) 
            VALUES ('$user_id', '$message')";

    if (mysqli_query($connect, $sql)) {
        header("Location: ../Student/Main_page.php");
    } else {
        echo "Error: " . mysqli_error($connect);
        header("Location: ../Student/Main_page.php");
    }

    mysqli_close($connect);
}
?>
