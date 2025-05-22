<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php'; 
include '../includes/connect_DB.php';

// 获取老师信息
$teacher_id = $_SESSION['user_id'];
$teacher_query = "SELECT T_Username FROM teacher WHERE teacher_id = ?";
$stmt = $connect->prepare($teacher_query);
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$teacher_result = $stmt->get_result();
$teacher_name = $teacher_result->fetch_assoc()['T_Username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .welcome-header {
            text-align: center;
            margin-bottom: 30px;
            color: rgb(142, 60, 181);
        }
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            border-left: 5px solid rgb(142, 60, 181);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .card h3 {
            color: rgb(142, 60, 181);
            margin-top: 0;
        }
        .card p {
            color: #666;
            margin-bottom: 20px;
        }
        .card-btn {
            display: inline-block;
            padding: 8px 16px;
            background: rgb(142, 60, 181);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .card-btn:hover {
            background: rgb(122, 40, 161);
        }
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            margin-top: 0;
            color: #555;
            font-size: 1rem;
        }
        .stat-card p {
            margin-bottom: 0;
            font-size: 1.5rem;
            font-weight: bold;
            color: rgb(142, 60, 181);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome-header">
            <h1>Welcome, <?php echo htmlspecialchars($teacher_name); ?></h1>
            <p>Teacher Dashboard</p>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <?php
            // 获取班级数量
            $class_count = 0;
            $class_query = "SELECT COUNT(*) as count FROM teacher_class WHERE teacher_id = ?";
            $stmt = $connect->prepare($class_query);
            $stmt->bind_param("s", $teacher_id);
            $stmt->execute();
            $class_result = $stmt->get_result();
            if ($class_row = $class_result->fetch_assoc()) {
                $class_count = $class_row['count'];
            }
            
            // 获取已分配课程数量
            $lesson_count = 0;
            $lesson_query = "SELECT COUNT(DISTINCT cw.lesson_id) as count 
                            FROM class_work cw
                            JOIN teacher_class tc ON cw.class_id = tc.class_id
                            WHERE tc.teacher_id = ?";
            $stmt = $connect->prepare($lesson_query);
            $stmt->bind_param("s", $teacher_id);
            $stmt->execute();
            $lesson_result = $stmt->get_result();
            if ($lesson_row = $lesson_result->fetch_assoc()) {
                $lesson_count = $lesson_row['count'];
            }
            
            // 获取待批改作业数量
            $submission_count = 0;
            $submission_query = "SELECT COUNT(*) as count 
                               FROM student_submit ss
                               JOIN class_work cw ON ss.lesson_id = cw.lesson_id AND ss.class_id = cw.class_id
                               JOIN teacher_class tc ON cw.class_id = tc.class_id
                               WHERE tc.teacher_id = ? AND ss.score IS NULL";
            $stmt = $connect->prepare($submission_query);
            $stmt->bind_param("s", $teacher_id);
            $stmt->execute();
            $submission_result = $stmt->get_result();
            if ($submission_row = $submission_result->fetch_assoc()) {
                $submission_count = $submission_row['count'];
            }
            ?>
            <div class="stat-card">
                <h3>Your Classes</h3>
                <p><?php echo $class_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Assigned Lessons</h3>
                <p><?php echo $lesson_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Submissions</h3>
                <p><?php echo $submission_count; ?></p>
            </div>
            <div class="stat-card">
                <h3>Teaching Materials</h3>
                <p>
                    <?php
                    $material_query = "SELECT COUNT(*) as count FROM teacher_materials WHERE teacher_id = ?";
                    $stmt = $connect->prepare($material_query);
                    $stmt->bind_param("s", $teacher_id);
                    $stmt->execute();
                    $material_result = $stmt->get_result();
                    echo $material_result->fetch_assoc()['count'];
                    ?>
                </p>
            </div>
        </div>

        <!-- Main Function Cards -->
        <div class="card-container">
            <!-- Content Management -->
            <div class="card">
                <h3>Content Management</h3>
                <p>Create and manage your lessons and teaching materials</p>
                <a href="lesson_management.php" class="card-btn">Manage Content</a>
            </div>

            <!-- Assign Work -->
            <div class="card">
                <h3>Assign Work</h3>
                <p>Assign lessons to your classes and share materials</p>
                <a href="availablework.php" class="card-btn">Assign Work</a>
            </div>

            <!-- View Submissions -->
            <div class="card">
                <h3>Student Submissions</h3>
                <p>View and grade student assignments</p>
                <a href="view_submissions.php" class="card-btn">View Submissions</a>
            </div>

            <!-- Quiz Management -->
            <div class="card">
                <h3>Quiz Management</h3>
                <p>Create and manage quiz questions</p>
                <a href="quizupload.php" class="card-btn">Manage Quizzes</a>
            </div>

            <!-- Class Management -->
            <div class="card">
                <h3>Class Management</h3>
                <p>View and manage your classes</p>
                <a href="view_class.php" class="card-btn">View Classes</a>
            </div>

            <!-- Discussion Board -->
            <div class="card">
                <h3>Discussion Board</h3>
                <p>View and participate in class discussions</p>
                <a href="assigned_lessons.php" class="card-btn">View Discussions</a>
            </div>
        </div>
    </div>

    <script src="../javascriptfile/teacher_main.js"></script>
</body>
</html>