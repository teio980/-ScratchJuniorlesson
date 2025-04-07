<?php
session_start();
include '../phpfile/connect.php'; 

$user_id = $_SESSION['user_id'];

$sql = "SELECT lesson_id, title, description,expire_date FROM lessons ORDER BY lesson_id ASC";
$result = $connect->query($sql);

$sql_quiz = "SELECT DISTINCT difficult FROM questions ORDER BY difficult ASC";
$result_quiz = $connect->query($sql_quiz);

include '../resheadAfterLogin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/reshead.css">
    <link rel="stylesheet" href="cssfile/Main_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Profile Page</title>
</head>
<body>

<h2>Available Lessons</h2>
<table border="1">
    <tr>
        <th>Lesson</th> 
        <th>Title</th>
        <th>Description</th>
        <th>Expired date</th>
        <th>Action</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['lesson_id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['expire_date']}</td>
                    <td>
                        <form action='studentsubmit.php' method='GET'>
                            <input type='hidden' name='lesson_id' value='{$row['lesson_id']}'>
                            <button type='submit'>Submit</button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No lessons available</td></tr>";
    }?>
</table>

    <h2>Quiz Levels</h2>
    <p>***above 80% unlock next level</p>
    <table border="1">
        <tr>
            <th>Level</th>
            <th>Available</th>
            <th>Correct</th>
            <th>Percentage</th>
            <th>Action</th>
            
        </tr>
        <?php
    if ($result_quiz->num_rows > 0) {
        $previous = true;
        while ($row = $result_quiz->fetch_assoc()) {
            $difficulty = $row['difficult'];

            $sql_correct = "SELECT COUNT(*) as correct FROM student_answers 
                            JOIN questions ON student_answers.question_id = questions.id
                            WHERE student_answers.student_id = $user_id 
                            AND questions.difficult = '$difficulty' 
                            AND student_answers.is_correct = 1";
            $result_correct = $connect->query($sql_correct);
            $total_correct = ($result_correct->num_rows > 0) ? $result_correct->fetch_assoc()['correct'] : 0;

            $sql_total = "SELECT COUNT(*) as total FROM questions WHERE difficult = '$difficulty'";
            $result_total = $connect->query($sql_total);
            $total_questions = ($result_total->num_rows > 0) ? $result_total->fetch_assoc()['total'] : 1;
    
            $score_display = "$total_correct / $total_questions";
            $percen = round(($total_correct / $total_questions) * 100, 2);

            if ($previous) {
                $lock_icon = "<i class='fa fa-check'></i>"; 
                $action_button = "<button><a href='Questionpaper.php?difficult=$difficulty'>Answer</a></button>";
                if($percen >=80){
                    $previous = true;
                }else{
                    $previous = false;
                }
            } else {
                $lock_icon = "<i class='fa fa-lock'></i>"; 
                $action_button = "<span>Unavailable</span>";
            }


            echo "<tr>
                    <td>Level $difficulty</td>
                    <td>$lock_icon</td>
                    <td>$score_display</td>
                    <td>$percen%</td>
                    <td>$action_button</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='2'>No quiz data available</td></tr>";
    }
    ?>
</table>

</body>
</html>
<?php
$connect->close();
?>
    