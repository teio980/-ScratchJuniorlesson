<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php'; 
include '../includes/connect_DB.php';
include 'resheadteacher.php';

// Add the CSS file 
echo '<link rel="stylesheet" href="../cssfile/Teachermain.css">';
echo '<link rel="stylesheet" href="../cssfile/resheadteacher.css">';
echo '<link rel="stylesheet" href="../cssfile/class_progress.css">';


// Get the class code from URL
$class_code = $_GET['class_id'] ?? '';

// Get class info
$class_query = "
    SELECT class_id, class_name, class_average, class_description
    FROM class
    WHERE class_code = '$class_code'
";
$class_result = mysqli_query($connect, $class_query);
$class_row = mysqli_fetch_assoc($class_result);

if ($class_row) {
    $class_id = $class_row['class_id'];
    $class_name = $class_row['class_name'];
    $class_average = $class_row['class_average'] ?? 0;
    $class_description = $class_row['class_description'] ?? '';
    $class_pass_status = $class_average >= 40 ? 'pass-true' : 'pass-false';
    $pass_icon = $class_average >= 40 ? '✔' : '✘';

    echo "<div class='class-container'>";
    echo "<div class='class-header'>";
    echo "<h1 class='class-title'>Class: " . htmlspecialchars($class_name) . "</h1>";
    echo "<p class='class-description'>" . htmlspecialchars($class_description) . "</p>";
    echo "<div class='class-average'>Class Average: $class_average% <span class='pass-status $class_pass_status'>$pass_icon</span></div>";
    echo "</div>";

    // Get total number of quizzes available
    $total_quizzes_query = "SELECT COUNT(DISTINCT difficult) as total FROM questions";
    $total_quizzes_result = mysqli_query($connect, $total_quizzes_query);
    $total_quizzes_row = mysqli_fetch_assoc($total_quizzes_result);
    $total_quizzes = $total_quizzes_row['total'] ?? 0;

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
        echo "<table class='students-table'>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>ID</th>
                        <th>Quiz Progress</th>
                        <th>Average Score</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            $student_id = $row['student_id'];
            $username = $row['S_username'];
            $student_average = $row['average_score'];
            
            // Get quiz progress for this student
            $quiz_progress_query = "
                SELECT q.difficult, 
                       SUM(sa.is_correct) as correct,
                       COUNT(*) as total
                FROM student_answers sa
                JOIN questions q ON sa.question_id = q.id
                WHERE sa.student_id = '$student_id'
                GROUP BY q.difficult
                ORDER BY q.difficult ASC
            ";
            $quiz_progress_result = mysqli_query($connect, $quiz_progress_query);
            
            $completed_quizzes = 0;
            $quiz_progress_data = [];
            
            while ($quiz_row = mysqli_fetch_assoc($quiz_progress_result)) {
                $difficulty = $quiz_row['difficult'];
                $correct = (int)$quiz_row['correct'];
                $total = (int)$quiz_row['total'];
                $percentage = $total > 0 ? round(($correct / $total) * 100, 2) : 0;
                
                if ($percentage >= 80) {
                    $completed_quizzes++;
                }
                
                $quiz_progress_data[] = [
                    'difficulty' => $difficulty,
                    'percentage' => $percentage
                ];
            }
            
            $quiz_percent = $total_quizzes > 0 ? round(($completed_quizzes / $total_quizzes) * 100, 2) : 0;
            
            // Student pass status
            $student_pass_status = $student_average !== null && $student_average >= 40 ? 'pass-true' : 'pass-false';
            $student_pass_icon = $student_average !== null && $student_average >= 40 ? '✔' : '✘';

            echo "<tr>
                    <td>" . htmlspecialchars($username) . "</td>
                    <td>" . htmlspecialchars($student_id) . "</td>
                    <td>
                        <div class='progress-container'>
                            <div class='progress-bar'>
                                <div class='progress-fill' style='width: {$quiz_percent}%'></div>
                            </div>
                            <div class='progress-text'>{$completed_quizzes}/{$total_quizzes}</div>
                        </div>
                        <div class='quiz-details'>" . implode(" • ", array_map(function($q) {
                                return "Quiz {$q['difficulty']} ({$q['percentage']}%)";
                            }, $quiz_progress_data)) . "</div>
                    </td>
                    <td class='average-score'>" . ($student_average !== null ? $student_average . "%" : "N/A") . "</td>
                    <td><span class='pass-status $student_pass_status'>$student_pass_icon</span></td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No students found for this class.</p>";
    }
    
    echo "</div>"; // Close class-container
} else {
    echo "<div class='class-container'><p>Class not found.</p></div>";
}
?>