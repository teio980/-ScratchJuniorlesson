<?php
session_start();
include '../includes/connect_DB.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $difficulty = intval($_POST['difficult']);
    $user_id = $_SESSION['user_id'];
    $user_answers = $_POST['answer'];

    $results = [];

    foreach ($user_answers as $question_id => $user_answer) {

        $sql = "SELECT answer FROM questions WHERE id = :question_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':question_id',$question_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $correct_answer = $stmt->fetch(PDO::FETCH_ASSOC)['answer'];

            $is_correct = ($user_answer == $correct_answer) ? 1 : 0;

            $results[] = [
                'user_id' => $user_id,
                'question_id' => $question_id,
                'user_answer' => $user_answer,
                'is_correct' => $is_correct
            ];
        }
    }

    foreach ($results as $result) {
        $user_id = $result['user_id'];
        $question_id = $result['question_id'];
        $user_answer = $result['user_answer'];
        $is_correct = $result['is_correct'];
        
        $sql = "INSERT INTO student_answers (student_id, question_id, student_answer, is_correct) 
                VALUES (:user_id, :question_id, :user_answer, :is_correct)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':question_id',$question_id);
        $stmt->bindParam(':user_answer',$user_answer);
        $stmt->bindParam(':is_correct',$is_correct);
        $stmt->execute();
    }
    echo "Answer Submitted";
}
?>