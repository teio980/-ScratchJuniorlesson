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
        $sqlclass = "SELECT class_id, class_code, class_name, current_capacity FROM class WHERE class_id = ?";
        $stmt = $connect->prepare($sqlclass);
        $stmt->bind_param("s", $class_id);
        $stmt->execute();
        $ClassResult = $stmt->get_result();

        if ($classInfo = $ClassResult->fetch_assoc()) {
            // 获取班级中学生总数（不管是否有分数）
            $studentCountQuery = "SELECT COUNT(*) as total FROM student_class WHERE class_id = '$class_id'";
            $countResult = mysqli_query($connect, $studentCountQuery);
            $studentCount = mysqli_fetch_assoc($countResult)['total'];

            // 获取所有有分数的学生的总分
            $getStudentsQuery = "
                SELECT SUM(sc.average_score) as total_score
                FROM student_class sc
                WHERE sc.class_id = '$class_id' 
                AND sc.average_score IS NOT NULL
            ";
            $studentResult = mysqli_query($connect, $getStudentsQuery);
            $scoreData = mysqli_fetch_assoc($studentResult);
            $totalScore = $scoreData['total_score'] ?? 0;

            // 计算班级平均分（用班级总学生数作为除数）
            $classAverage = 0;
            if ($studentCount > 0) {
                $classAverage = min(round($totalScore / $studentCount), 100);
                
                // 更新数据库中的班级平均分
                $updateClassQuery = "UPDATE class SET class_average = ? WHERE class_id = ?";
                $updateStmt = $connect->prepare($updateClassQuery);
                $updateStmt->bind_param("ds", $classAverage, $class_id);
                $updateStmt->execute();
                $updateStmt->close();
            }
            
            $classInfo['class_average'] = $classAverage;
            $classInfo['student_count'] = $studentCount;
            $classList[] = $classInfo;
        }
        $stmt->close();
    }
}
?>

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
                <th>Pass Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                if (!empty($classList)) {
                    foreach ($classList as $class) {
                        $passStatus = $class['class_average'] >= 40 ? '✔' : '✘';
            ?>
                <tr>
                    <td>
                        <a href="view_onceclass.php?class_id=<?php echo $class['class_code']; ?>">
                            <?php echo htmlspecialchars($class['class_code']); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                    <td><?php echo htmlspecialchars($class['student_count']); ?></td>
                    <td><?php echo $class['class_average']; ?>%</td>
                    <td><?php echo $passStatus; ?></td>
                </tr>
            <?php 
                    }
                } else {
                    echo "<tr><td colspan='5'>No classes found.</td></tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>