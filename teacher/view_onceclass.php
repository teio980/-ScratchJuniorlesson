<?php
session_start();
include '../phpfile/connect.php';
include '../resheadAfterLogin.php';

// 获取传递过来的 class_code
$class_code = $_GET['class_id'] ?? '';

// 根据 class_code 获取 class_id
$class_query = "
    SELECT class_id
    FROM class
    WHERE class_code = '$class_code'
";
$class_result = mysqli_query($connect, $class_query);
$class_row = mysqli_fetch_assoc($class_result);

if ($class_row) {
    $class_id = $class_row['class_id'];

    // 查询该班级的所有学生
    $query = "
        SELECT s.student_id, s.S_username
        FROM student_class sc
        JOIN student s ON sc.student_id = s.student_id
        WHERE sc.class_id = '$class_id'
    ";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Student Username</th>
                    <th>Student ID</th>
                    <th>Quiz Score (Reference Only)</th>
                    <th>Lesson Score</th>
                    <th>Pass</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            $student_id = $row['student_id'];
            $username = $row['S_username'];

            // 获取该学生的所有评分平均值作为 Lesson Score（上限100）
            $lesson_query = "
                SELECT AVG(rating) AS avg_rating
                FROM ratings
                WHERE student_id = '$student_id'
            ";
            $lesson_result = mysqli_query($connect, $lesson_query);
            $lesson_score = null;

            if ($lesson_row = mysqli_fetch_assoc($lesson_result)) {
                $avg_rating = (float)$lesson_row['avg_rating'];
                if ($avg_rating > 0) {
                    $lesson_score = min(round($avg_rating, 2), 100);
                }
            }

            // 获取该学生的 Quiz 分数（仅用于显示，不参与计算）
            $quiz_query = "
                SELECT SUM(sa.is_correct) AS correct_count, COUNT(*) AS total_count
                FROM student_answers sa
                JOIN student_class sc ON sa.student_id = sc.student_id
                WHERE sa.student_id = '$student_id' AND sc.class_id = '$class_id'
            ";
            $quiz_result = mysqli_query($connect, $quiz_query);
            $quiz_score = null;

            if ($quiz_row = mysqli_fetch_assoc($quiz_result)) {
                $correct = (int)$quiz_row['correct_count'];
                $total = (int)$quiz_row['total_count'];
                if ($total > 0) {
                    $quiz_score = round(($correct / $total) * 100, 2);
                }
            }

            $passed = $lesson_score !== null && $lesson_score >= 40 ? '✔' : '✘';

            $quiz_display = $quiz_score !== null ? $quiz_score . "%" : "N/A";
            $lesson_display = $lesson_score !== null ? $lesson_score : "N/A";

            echo "<tr>
                    <td>" . htmlspecialchars($username) . "</td>
                    <td>" . htmlspecialchars($student_id) . "</td>
                    <td>$quiz_display</td>
                    <td>$lesson_display</td>
                    <td>$passed</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "No students found for this class.";
    }
} else {
    echo "Class not found.";
}
?>