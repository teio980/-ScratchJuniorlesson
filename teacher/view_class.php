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

        // Get class basic info
        $sqlclass = "SELECT class_id, class_code, class_name, current_capacity FROM class WHERE class_id = ?";
        $stmt = $connect->prepare($sqlclass);
        $stmt->bind_param("s", $class_id);
        $stmt->execute();
        $ClassResult = $stmt->get_result();

        if ($classInfo = $ClassResult->fetch_assoc()) {
            $studentAverages = [];
            $studentCount = 0;

            // Get all students in class with their averages
            $getStudentsQuery = "
                SELECT s.student_id, s.student_average
                FROM student_class sc
                JOIN student s ON sc.student_id = s.student_id
                WHERE sc.class_id = '$class_id' AND s.student_average IS NOT NULL
            ";
            $studentResult = mysqli_query($connect, $getStudentsQuery);

            if ($studentResult && mysqli_num_rows($studentResult) > 0) {
                while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                    if ($studentRow['student_average'] !== null) {
                        $studentAverages[] = $studentRow['student_average'];
                        $studentCount++;
                    }
                }
            }

            // Calculate class average score
            $classAverage = 0;
            if (count($studentAverages) > 0) {
                $classAverage = min(round(array_sum($studentAverages) / count($studentAverages)), 100);
                
                // Update class average in database
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