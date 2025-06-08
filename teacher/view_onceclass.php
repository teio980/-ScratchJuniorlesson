<?php
session_start();
include '../phpfile/connect.php';
include '../resheadAfterLogin.php';

// Get the class code from URL
$class_code = $_GET['class_id'] ?? '';

// Get class info
$class_query = "
    SELECT class_id, class_name, class_average
    FROM class
    WHERE class_code = '$class_code'
";
$class_result = mysqli_query($connect, $class_query);
$class_row = mysqli_fetch_assoc($class_result);

if ($class_row) {
    $class_id = $class_row['class_id'];
    $class_name = $class_row['class_name'];
    $class_average = $class_row['class_average'] ?? 0;
    $class_pass_status = $class_average >= 40 ? '✔' : '✘';

    echo "<h2>Class: " . htmlspecialchars($class_name) . "</h2>";
    echo "<h3>Class Average Score: $class_average% ($class_pass_status)</h3>";

    // Query all students in this class with their averages from student_class
    $query = "
        SELECT s.student_id, s.S_username, sc.average_score
        FROM student_class sc
        JOIN student s ON sc.student_id = s.student_id
        WHERE sc.class_id = '$class_id'
        ORDER BY s.S_username
    ";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Student Username</th>
                    <th>Student ID</th>
                    <th>Quiz Score (Reference Only)</th>
                    <th>Lesson Average Score</th>
                    <th>Pass Status</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            $student_id = $row['student_id'];
            $username = $row['S_username'];
            $student_average = $row['average_score'];
            
            // Get quiz score (for reference only)
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

            $passed = $student_average !== null && $student_average >= 40 ? '✔' : '✘';

            $quiz_display = $quiz_score !== null ? $quiz_score . "%" : "N/A";
            $lesson_display = $student_average !== null ? $student_average . "%" : "N/A";

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
        echo "<p>No students found for this class.</p>";
    }
} else {
    echo "<p>Class not found.</p>";
}
?>