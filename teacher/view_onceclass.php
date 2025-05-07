<?php
session_start();
include '../phpfile/connect.php';
include '../resheadAfterLogin.php';

$class_id = $_GET['class_id'];

$query = "
    SELECT 
        s.student_id, 
        s.S_username, 
        sa.difficult, 
        COUNT(*) AS total_attempts,
        SUM(sa.is_correct = 1) AS correct_count
    FROM student_class sc
    JOIN student s ON sc.student_id = s.student_id
    JOIN student_answers sa ON s.student_id = sa.student_id
    WHERE sc.class_id = '$class_id'
    GROUP BY s.student_id, sa.difficult
    ORDER BY s.student_id, sa.difficult
";

$result = mysqli_query($connect, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>
            <tr>
                <th>Student Username</th>
                <th>Student ID</th>
                <th>Quiz</th>
                <th>Correct Answers</th>
                <th>Total Question</th>
                <th>Percentage</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $correct = (int)$row['correct_count'];
        $total = (int)$row['total_attempts'];
        $percent = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        echo "<tr>
                <td>" . htmlspecialchars($row['S_username']) . "</td>
                <td>" . htmlspecialchars($row['student_id']) . "</td>
                <td>" . htmlspecialchars($row['difficult']) . "</td>
                <td>$correct</td>
                <td>$total</td>
                <td>$percent%</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "No quiz results found for this class.";
}
?>
<!--i love kum -->