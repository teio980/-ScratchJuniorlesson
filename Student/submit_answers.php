<?php
session_start();
include '../includes/connect_DB.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quiz Result</title>
  <link rel="stylesheet" href="../cssfile/quizpaper.css"> 
</head>
<body>
<?php
if (isset($_POST['savebtn'])) {
    $difficulty = $_POST['difficult'];
    $user_id = $_SESSION['user_id'];
    $user_answers = $_POST['answer'];

    $base_xp_per_question = 20;
    $multiplier = 1.2;
    $bonus_xp = 0;
    $total_questions = 0;
    $correct_answers = 0;
    $xp_earned = 0;

    // Delete old answers for this quiz
    $sql_delete = "DELETE FROM student_answers WHERE student_id = ? AND question_id IN (SELECT id FROM questions WHERE difficult = ?)";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([$user_id, $difficulty]);

    // Handle answers
    foreach ($user_answers as $question_id => $user_answer) {
        $question_id = intval($question_id);
        $user_answer = addslashes($user_answer);

        $stmt_q = $pdo->prepare("SELECT answer FROM questions WHERE id = ?");
        $stmt_q->execute([$question_id]);

        if ($stmt_q->rowCount() > 0) {
            $row = $stmt_q->fetch(PDO::FETCH_ASSOC);
            $correct_answer = $row['answer'];
            $is_correct = ($user_answer == $correct_answer) ? 1 : 0;

            $total_questions++;

            if ($is_correct) {
                $correct_answers++;

                // Calculate XP with multiplier
                $xp_earned += $base_xp_per_question * pow($multiplier, $correct_answers - 1);
            }

            // Insert into student_answers
            $stmt_count = $pdo->query("SELECT COUNT(*) FROM student_answers");
            $sq_id = 'SQ' . str_pad($stmt_count->fetchColumn() + 1, 6, '0', STR_PAD_LEFT);

            $stmt_insert = $pdo->prepare("INSERT INTO student_answers (student_question_id, student_id, question_id, student_answer, is_correct, difficult) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->execute([$sq_id, $user_id, $question_id, $user_answer, $is_correct, $difficulty]);
        }
    }

    $score = ($correct_answers / $total_questions) * 100;

    // Bonus XP if 100%
    if ($correct_answers === $total_questions) {
        $bonus_xp = 50;
    }

    $totalxp = round($xp_earned + $bonus_xp);

    // Update experience only if passed (>=80%)
    if ($score >= 80) {
        $stmt_check = $pdo->prepare("SELECT experience FROM student_level WHERE student_id = ?");
        $stmt_check->execute([$user_id]);

        if ($stmt_check->rowCount() > 0) {
            $current_xp = $stmt_check->fetchColumn();
            $new_xp = $current_xp + $totalxp;

            $stmt_update = $pdo->prepare("UPDATE student_level SET experience = ? WHERE student_id = ?");
            $stmt_update->execute([$new_xp, $user_id]);
        } else {
            $stmt_insert_level = $pdo->prepare("INSERT INTO student_level (student_id, experience) VALUES (?, ?)");
            $stmt_insert_level->execute([$user_id, $totalxp]);
        }

        // Fetch updated experience to display
        $stmt_fetch_updated = $pdo->prepare("SELECT experience FROM student_level WHERE student_id = ?");
        $stmt_fetch_updated->execute([$user_id]);
        $updated_total_xp = $stmt_fetch_updated->fetchColumn();
        $updated_total_xp = $updated_total_xp ?: 0;
    }

    // Calculate SVG circle
    $circle_percentage = $score;
    $circle_radius = 54;
    $circumference = 2 * pi() * $circle_radius;
    $offset = $circumference - ($circumference * $circle_percentage / 100);

    echo "<div class='quizbody'>
            <div class='quiz-container'>
            <h2 class='quiz-title'>Your Quiz $difficulty Result</h2>

            <div class='circle-progress-container'>
                <svg class='circle-progress' viewBox='0 0 120 120'>
                    <circle class='circle-background' cx='60' cy='60' r='54'></circle>
                    <circle class='circle-fill' cx='60' cy='60' r='54' stroke-dasharray='$circumference' stroke-dashoffset='$offset'></circle>
                    <text class='circle-text' x='50%' y='50%' text-anchor='middle' dy='.3em'>$correct_answers / $total_questions</text>
                </svg>
            </div>";

    // XP summary
    if ($score >= 80) {
        echo "<div style='font-size: 18px; margin-top: 10px;'>";
        echo "+".round($xp_earned)." XP from correct answers<br>";
        if ($bonus_xp > 0) {
            echo "+$bonus_xp XP Bonus (100% Combo)<br>";
        }
        echo "Total Gained: +$totalxp XP<br>";
        echo "</div>";
    }

    // Buttons
    if ($score >= 80) {
        echo "<button onclick='window.location.href=\"Main_page.php\"' class='submit-btn'>Return</button>";
        if ($score >= 100) {
            echo "<p style='font-size: 20px;'>Congratulations!!!</p>";
        }
    } else {
        echo "<button onclick='window.location.href=\"Questionpaper.php?difficult=$difficulty\"' class='submit-btn second'>Attempt again</button>";
        echo "<button onclick='window.location.href=\"Main_page.php\"' class='submit-btn second'>Return</button>";
    }

    echo "</div></div>";

    $xp_check_stmt = $pdo->prepare("SELECT experience, level FROM student_level WHERE student_id = ?");
    $xp_check_stmt->execute([$user_id]);
    
    if ($xp_row = $xp_check_stmt->fetch(PDO::FETCH_ASSOC)) {
        $current_xp = (int)$xp_row['experience'];
        $current_level = (int)$xp_row['level'];
    
        $level = $current_level;
        $xp = $current_xp;
    
        // Loop to calculate how many levels the student gains
        while (true) {
            $xp_needed = 100 + ($level - 1) * 50;
    
            if ($xp >= $xp_needed) {
                $xp -= $xp_needed;
                $level++;
            } else {
                break;
            }
        }
    
        // If the level has changed, update the database
        if ($level != $current_level) {
            $stmt_update_lvl = $pdo->prepare("UPDATE student_level SET experience = ?, level = ? WHERE student_id = ?");
            $stmt_update_lvl->execute([$xp, $level, $user_id]);
        }
    }
    



    exit();
}
?>
</body>
</html>
