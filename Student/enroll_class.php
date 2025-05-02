<?php
    session_start();
    include '../includes/connect_DB.php';
    $studentID = $_SESSION['user_id'];

    $checkStudentClassSql = "SELECT COUNT(*) FROM student_class WHERE student_id = :studentID";
    $checkStudentClassStmt = $pdo->prepare($checkStudentClassSql);
    $checkStudentClassStmt->bindParam(':studentID', $studentID);
    $checkStudentClassStmt->execute();
    $hasClass = (bool)$checkStudentClassStmt->fetchColumn();

    $selectClassSql = "SELECT class_id, class_code, class_name, class_description AS Description, max_capacity AS Max, current_capacity AS Cur FROM class WHERE max_capacity > current_capacity";
    $selectClassStmt = $pdo->prepare($selectClassSql);
    $selectClassStmt->execute();
    $classes = $selectClassStmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($classes as $class) {
        $teacher_sql = "SELECT t.T_Username
                        FROM teacher_class tc
                        JOIN teacher t ON tc.teacher_id = t.teacher_id
                        WHERE tc.class_id = :class_id";
        
        $stmt = $pdo->prepare($teacher_sql);
        $stmt->bindValue(':class_id', $class['class_id']);
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $class['teachers'] = $teachers;
        $result[] = $class;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/enrollClass.css">
    <title>Enroll Class</title>
</head>
<body>
    <form action="../includes/process_enroll_class.php" class="enroll_form" method="post">
        <?php foreach ($result as $class): ?>
            <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class['class_id']) ?>">
            <input type="hidden" name="Max" value="<?php echo htmlspecialchars($class['Max']) ?>">
            <input type="hidden" name="Current" value="<?php echo htmlspecialchars($class['Cur']) ?>">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($studentID)?>">
            <div class="enrollment_container">
                <div><img src="path_to_class_image" alt="Class Image"></div>
                <div>
                    <h4><?php echo htmlspecialchars($class['class_name']) ?></h4>
                    <p>Teach by: <?php 
                        $teacherNames = array();
                        foreach ($class['teachers'] as $teacher) {
                            $teacherNames[] = htmlspecialchars($teacher['T_Username']);
                        }
                    ?></p>
                    <p><?php echo htmlspecialchars($class['Description']) ?></p>
                    <div class="capacity_box">
                        <div>Max Capacity: <?php echo htmlspecialchars($class['Max']) ?></div>
                        <div>Current: <?php echo htmlspecialchars($class['Cur']) ?></div>
                    </div>
                    <div ><button type="submit" class="enroll_btn" name ="enroll_btn">Enroll</button></div>
                </div>
            </div>
        <?php endforeach; ?>
    </form>
</body>
<script>
const hasClass = <?php echo $hasClass ? 'true' : 'false'; ?>;

if (hasClass) {
    document.querySelectorAll('.enroll_btn').forEach(button => {
        button.style.display = 'none';
    });
}
</script>
</html>