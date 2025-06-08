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
    <title>Assign Content to Classes</title>
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/availablework.css">
    <style>
        .section-container {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .section-title {
            color: rgb(142, 60, 181);
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Lessons Assignment Section -->
        <div class="section-container">
            <h2 class="section-title">Assign Lessons to Class</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Select Class:</label>
                    <select name="class_id" required>
                        <option value="">-- Select a Class --</option>
                        <?php
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
                    <select name="lesson_id" required>
                        <option value="">-- Select a Lesson --</option>
                        <?php
                        $lessonResult = $connect->query("SELECT lesson_id, title FROM lessons");
                        while ($lesson = $lessonResult->fetch_assoc()) {
                            echo "<option value='{$lesson['lesson_id']}'>{$lesson['title']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Set Deadline:</label>
                    <input type="datetime-local" name="expire_date" min="<?= date('Y-m-d\TH:i') ?>" required>
                </div>

                <button type="submit" name="assign_submit">Assign Lesson</button>
            </form>
        </div>

        <!-- Materials Sharing Section -->
        <div class="section-container">
            <h2 class="section-title">Share Materials with All Your Classes</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Select Material:</label>
                    <select name="material_id" required>
                        <option value="">-- Select a Material --</option>
                        <?php
                        $materialQuery = "SELECT material_id, title FROM teacher_materials 
                                         WHERE teacher_id = ?";
                        $stmt = $connect->prepare($materialQuery);
                        $stmt->bind_param("s", $teacher_id);
                        $stmt->execute();
                        $materialResult = $stmt->get_result();
                        
                        while ($material = $materialResult->fetch_assoc()) {
                            echo "<option value='{$material['material_id']}'>{$material['title']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" name="share_material">Share to Classes</button>
            </form>
        </div>
    </div>

<?php
// 处理Lesson分配
if (isset($_POST['assign_submit'])) {
    $class_id = $_POST['class_id'];
    $lesson_id = $_POST['lesson_id'];
    $expire_date = $_POST['expire_date'];
    
    // 验证权限
    $checkQuery = "SELECT 1 FROM teacher_class 
                  WHERE teacher_id = ? AND class_id = ?";
    $stmt = $connect->prepare($checkQuery);
    $stmt->bind_param("ss", $teacher_id, $class_id);
    $stmt->execute();
    $checkResult = $stmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        echo "<script>alert('You are not authorized to assign lessons to this class.');</script>";
        exit();
    }
    
    // 检查是否已分配
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
    
    // 获取课程文件
    $lessonQuery = "SELECT file_name FROM lessons WHERE lesson_id = ?";
    $stmt = $connect->prepare($lessonQuery);
    $stmt->bind_param("s", $lesson_id);
    $stmt->execute();
    $stmt->bind_result($lesson_file_name);
    
    if ($stmt->fetch()) {
        $stmt->close();
        
        // 生成ID
        $sql = "SELECT COUNT(*) AS total FROM class_work";
        $result = $connect->query($sql);
        $row = $result->fetch_assoc();
        $classwork_id = 'CW' . str_pad($row['total'] + 1, 6, '0', STR_PAD_LEFT);
        
        // 插入记录
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
            echo "<script>alert('Lesson assigned successfully!'); window.location.href = 'Main_page.php';</script>";
        } else {
            echo "<script>alert('Failed to assign lesson.');</script>";
        }
    } else {
        echo "<script>alert('Lesson not found.');</script>";
    }
}

// 处理Material共享
if (isset($_POST['share_material'])) {
    $material_id = $_POST['material_id'];
    
    // 获取老师的所有班级
    $classQuery = "SELECT class_id FROM teacher_class 
                  WHERE teacher_id = ?";
    $stmt = $connect->prepare($classQuery);
    $stmt->bind_param("s", $teacher_id);
    $stmt->execute();
    $classResult = $stmt->get_result();
    
    // 更新每个班级的material关联
    while ($class = $classResult->fetch_assoc()) {
        $updateQuery = "UPDATE teacher_materials 
                       SET class_id = ?
                       WHERE material_id = ?";
        $stmt = $connect->prepare($updateQuery);
        $stmt->bind_param("ss", $class['class_id'], $material_id);
        $stmt->execute();
    }
    
    echo "<script>alert('Material shared to all your classes successfully!');</script>";
}
?>
</body>
</html>