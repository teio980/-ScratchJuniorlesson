<?php
session_start();
include '../includes/connect_DB.php'; 

if (isset($_POST['savebtn'])) {
    $difficulty = $_POST['difficult'];
    $user_id = $_SESSION['user_id'];
    $user_answers = $_POST['answer'];

    $sql_delete = "DELETE FROM student_answers WHERE student_id = $user_id AND question_id IN (SELECT id FROM questions WHERE difficult = $difficulty)";
    $pdo->query($sql_delete);

    foreach ($user_answers as $question_id => $user_answer) {
        $question_id = intval($question_id);
        $user_answer = addslashes($user_answer);

        $sql = "SELECT answer FROM questions WHERE id = $question_id";
        $result = $pdo->query($sql);

        if ($result && $result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $correct_answer = $row['answer'];
            $is_correct = ($user_answer == $correct_answer) ? 1 : 0;

            $sql_insert = "INSERT INTO student_answers (student_id, question_id, student_answer, is_correct, difficulty)
                           VALUES ($user_id, $question_id, '$user_answer', $is_correct, $difficulty)";
            $pdo->query($sql_insert);
        }
    }


    header("Location: Main_page.php");
    exit();
}
?>
