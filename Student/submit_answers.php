<?php
session_start();
include '../includes/connect_DB.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Level <?php echo $difficulty; ?></title>
  <link rel="stylesheet" href="../cssfile/quizpaper.css"> 
</head>
<body>
    <?php
    if (isset($_POST['savebtn'])) {
        $difficulty = $_POST['difficult'];
        $user_id = $_SESSION['user_id'];
        $user_answers = $_POST['answer'];

        $sql_delete = "DELETE FROM student_answers WHERE student_id = '$user_id' AND question_id IN (SELECT id FROM questions WHERE difficult = $difficulty)";
        $pdo->query($sql_delete);

        $total_questions = 0;
        $correct_answers = 0;

        foreach ($user_answers as $question_id => $user_answer) {
            $question_id = intval($question_id);
            $user_answer = addslashes($user_answer);

            $sql = "SELECT answer FROM questions WHERE id = $question_id";
            $result = $pdo->query($sql);

            if ($result && $result->rowCount() > 0) {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $correct_answer = $row['answer'];
                $is_correct = ($user_answer == $correct_answer) ? 1 : 0;

                $total_questions++;

                if ($is_correct) {
                    $correct_answers++;
                }

                $sql_T_checkQty = "SELECT COUNT(*) FROM student_answers";
                $stmt_T_checkQty = $pdo->prepare($sql_T_checkQty);
                $stmt_T_checkQty->execute();
                $sq_Qty = $stmt_T_checkQty->fetchColumn();
                $sq_id = 'SQ'.str_pad($sq_Qty + 1, 6, '0', STR_PAD_LEFT);

                $sql_insert = "INSERT INTO student_answers (student_question_id, student_id, question_id, student_answer, is_correct, difficult)
                            VALUES ('$sq_id','$user_id', $question_id, '$user_answer', $is_correct, $difficulty)";
                $pdo->query($sql_insert);
            }
        }

        $score = ($correct_answers / $total_questions) * 100;

        $circle_percentage = ($correct_answers / $total_questions) * 100;
        $circle_radius = 54; 
        $circumference = 2 * pi() * $circle_radius; 
        $offset = $circumference - ($circumference * $circle_percentage / 100); 

        echo "  <div class='quizbody'>
                <div class='quiz-container'>
                <h2 class='quiz-title'>Your Quiz $difficulty Result</h2>

                <div class='circle-progress-container'>
                    <svg class='circle-progress' viewBox='0 0 120 120' xmlns='http://www.w3.org/2000/svg'>
                        <circle class='circle-background' cx='60' cy='60' r='54'></circle>
                        <circle class='circle-fill' cx='60' cy='60' r='54' stroke-dasharray='$circumference' stroke-dashoffset='$offset'></circle>
                        <text class='circle-text' x='50%' y='50%' text-anchor='middle' dy='.3em'>$correct_answers / $total_questions</text>
                    </svg>
                </div>";

        if ($score >= 80) {
            echo "<button onclick='window.location.href=\"Main_page.php\"' class='submit-btn'>Return</button>";
            if($score >= 100){
                echo "<p style='font-size: 20px;'>Congratulations!!!</p>";
            }
        } else {
            echo "<button onclick='window.location.href=\"Questionpaper.php?difficult=$difficulty\"' class='submit-btn second'>Attempt again</button>";
            echo "<button onclick='window.location.href=\"Main_page.php\"' class='submit-btn second'>Return</button>";
        }

        echo "</div>";
        echo "</div>";
        exit();
    }
    ?>
</body>
</html>
