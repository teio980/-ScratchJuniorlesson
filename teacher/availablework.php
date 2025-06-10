<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php'; 
include '../includes/connect_DB.php';
include 'resheadteacher.php';

$teacher_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Lesson to Class</title>
    <link rel="stylesheet" href="../cssfile/Teachermain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/availablework.css">
</head>
<body>
    <div class="container">
        <!-- 课程分配部分 -->
        <div class="section-container">
            <h2 class="section-title">Assign Lesson to Class</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Select Class:</label>
                    <select name="class_id" id="classSelect" required onchange="updateLessonOptions()">
                        <option value="">-- Select Class --</option>
                        <?php
                        // 查询老师所教的班级
                        $classQuery = "SELECT c.class_id, c.class_name 
                                     FROM class c
                                     JOIN teacher_class tc ON c.class_id = tc.class_id
                                     WHERE tc.teacher_id = ?";
                        $stmt = $connect->prepare($classQuery);
                        $stmt->bind_param("s", $teacher_id);
                        $stmt->execute();
                        $classResult = $stmt->get_result();
                        
                        while ($class = $classResult->fetch_assoc()) {
                            echo "<option value='{$class['class_id']}'>{$class['class_name']} ({$class['class_id']})</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Select Lesson:</label>
                    <select name="lesson_id" id="lessonSelect" required>
                        <option value="">-- Select Lesson --</option>
                        <?php
                        // 查询所有课程
                        $lessonResult = $connect->query("SELECT lesson_id, title FROM lessons");
                        while ($lesson = $lessonResult->fetch_assoc()) {
                            echo "<option value='{$lesson['lesson_id']}'>{$lesson['title']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Set Expiration Date:</label>
                    <input type="datetime-local" name="expire_date" min="<?= date('Y-m-d\\TH:i') ?>" required>
                </div>

                <button type="submit" name="assign_submit">Assign Lesson</button>
            </form>
        </div>
    </div>

<script>
    // 更新课程选项的函数
    function updateLessonOptions() {
        const classId = document.getElementById('classSelect').value;
        if (!classId) return;
    }
</script>

<?php
// 处理课程分配
if (isset($_POST['assign_submit'])) {
    $class_id = $_POST['class_id'];
    $lesson_id = $_POST['lesson_id'];
    $expire_date = $_POST['expire_date'];
    
    // 验证老师是否有权限给这个班级分配课程
    $checkQuery = "SELECT 1 FROM teacher_class 
                  WHERE teacher_id = ? AND class_id = ?";
    $stmt = $connect->prepare($checkQuery);
    $stmt->bind_param("ss", $teacher_id, $class_id);
    $stmt->execute();
    $checkResult = $stmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        echo "<script>alert('You do not have permission to assign lessons to this class.');</script>";
        exit();
    }
    
    // 检查是否已经分配过这个课程
    $duplicateQuery = "SELECT 1 FROM class_work 
                      WHERE class_id = ? AND lesson_id = ?";
    $stmt = $connect->prepare($duplicateQuery);
    $stmt->bind_param("ss", $class_id, $lesson_id);
    $stmt->execute();
    $duplicateResult = $stmt->get_result();
    
    if ($duplicateResult->num_rows > 0) {
        echo "<script>alert('This lesson has already been assigned to the selected class.');</script>";
        exit();
    }
    
    // 获取课程文件信息
    $lessonQuery = "SELECT file_name FROM lessons WHERE lesson_id = ?";
    $stmt = $connect->prepare($lessonQuery);
    $stmt->bind_param("s", $lesson_id);
    $stmt->execute();
    $stmt->bind_result($lesson_file_name);
    
    if ($stmt->fetch()) {
        $stmt->close();
        
        // 生成唯一的课程分配ID
        $sql = "SELECT COUNT(*) AS total FROM class_work";
        $result = $connect->query($sql);
        $row = $result->fetch_assoc();
        $classwork_id = 'CW' . str_pad($row['total'] + 1, 6, '0', STR_PAD_LEFT);
        
        // 插入分配记录
        $insert_query = "INSERT INTO class_work 
                        (availability_id, lesson_id, class_id, student_work, expire_date) 
                        VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $connect->prepare($insert_query);
        $insert_stmt->bind_param("sssss", 
            $classwork_id, 
            $lesson_id, 
            $class_id, 
            $lesson_file_name,
            $expire_date
        );
        
        if ($insert_stmt->execute()) {
            echo "<script>alert('Lesson assigned successfully!'); window.location.href = 'availablework.php';</script>";
        } else {
            echo "<script>alert('Lesson assignment failed.');</script>";
        }
    } else {
        echo "<script>alert('Lesson not found.');</script>";
    }
}
?>
</body>
</html>
