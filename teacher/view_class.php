<?php
session_start();
include '../phpfile/connect.php';

$teacher_id = $_SESSION['user_id'];
$teacherClasssql = "SELECT class_id FROM teacher_class WHERE teacher_id = '$teacher_id'";
$Classid = mysqli_query($connect, $teacherClasssql);

$classList = [];

if ($Classid && mysqli_num_rows($Classid) > 0) {
    while ($row = mysqli_fetch_assoc($Classid)) {
        $class_id = $row['class_id'];

        // 获取班级基本信息
        $sqlclass = "SELECT class_code, class_name, current_capacity FROM class WHERE class_id = ?";
        $stmt = $connect->prepare($sqlclass);
        $stmt->bind_param("s", $class_id);
        $stmt->execute();
        $ClassResult = $stmt->get_result();

        if ($classInfo = $ClassResult->fetch_assoc()) {
            $studentScores = [];

            // 获取班级中所有学生
            $getStudentsQuery = "
                SELECT student_id
                FROM student_class
                WHERE class_id = '$class_id'
            ";
            $studentResult = mysqli_query($connect, $getStudentsQuery);

            if ($studentResult && mysqli_num_rows($studentResult) > 0) {
                while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                    $student_id = $studentRow['student_id'];

                    // 获取该学生 lesson 分数（评分平均值）
                    $lessonQuery = "
                        SELECT AVG(rating) AS avg_rating
                        FROM ratings
                        WHERE student_id = '$student_id'
                    ";
                    $lessonResult = mysqli_query($connect, $lessonQuery);
                    $lesson_score = null;

                    if ($lessonRow = mysqli_fetch_assoc($lessonResult)) {
                        $avg_rating = (float)$lessonRow['avg_rating'];
                        if ($avg_rating > 0) {
                            $lesson_score = min(round($avg_rating, 2), 100);
                        }
                    }

                    // 添加学生分数用于班级平均计算（仅使用lesson_score）
                    if ($lesson_score !== null) {
                        $studentScores[] = $lesson_score;
                    }
                }
            }

            // 计算班级平均分（学生lesson_score的平均值）
            if (count($studentScores) > 0) {
                $averageScore = round(array_sum($studentScores) / count($studentScores), 2);
            } else {
                $averageScore = 0;
            }

            $classInfo['average_score'] = $averageScore;
            $classList[] = $classInfo;
        }

        $stmt->close();
    }
}
?>

<!-- HTML 显示部分 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Class</title>
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <link rel="stylesheet" href="../cssfile/view_ssub.css">
</head>
<body>
    <h2>View Class</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Class CODE</th>
                <th>Class Name</th>
                <th>Student Number</th>
                <th>Average Score</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if (!empty($classList)) {
                    foreach ($classList as $class) {
            ?>
                <tr>
                    <td>
                        <a href="view_onceclass.php?class_id=<?php echo $class['class_code']; ?>">
                            <?php echo htmlspecialchars($class['class_code']); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                    <td><?php echo htmlspecialchars($class['current_capacity']); ?></td>
                    <td><?php echo $class['average_score']; ?></td>
                </tr>
            <?php 
                    }
                } else {
                    echo "<tr><td colspan='4'>No classes found.</td></tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>