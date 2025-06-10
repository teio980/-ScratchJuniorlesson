<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php'; 
include '../includes/connect_DB.php';
include 'resheadteacher.php';

$teacher_id = $_SESSION['user_id'];
$teacher_query = "SELECT T_Username FROM teacher WHERE teacher_id = ?";
$stmt = $connect->prepare($teacher_query);
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$teacher_result = $stmt->get_result();
$teacher_name = $teacher_result->fetch_assoc()['T_Username'];

$mini_game_count = 0;
$mini_game_query = "SELECT COUNT(*) as count FROM mini_games WHERE teacher_id = ?";
$stmt = $connect->prepare($mini_game_query);
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$mini_game_result = $stmt->get_result();
if ($mini_game_row = $mini_game_result->fetch_assoc()) {
    $mini_game_count = $mini_game_row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <title>Teacher Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome-header">
            <h1>Welcome, <?php echo htmlspecialchars($teacher_name); ?></h1>
            <p>Teacher Dashboard</p>
        </div>

        <div class="quick-stats">
            <?php
            $class_count = 0;
            $class_query = "SELECT COUNT(*) as count FROM teacher_class WHERE teacher_id = ?";
            $stmt = $connect->prepare($class_query);
            $stmt->bind_param("s", $teacher_id);
            $stmt->execute();
            $class_result = $stmt->get_result();
            if ($class_row = $class_result->fetch_assoc()) {
                $class_count = $class_row['count'];
            }
            
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
                <h3>Pending Submissions</h3>
                <p><?php echo $submission_count; ?></p>
            </div>

            <div class="stat-card">
                <h3>Assigned Lessons</h3>
                <p><?php echo $lesson_count; ?></p>
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

            <div class="stat-card">
                <h3>Mini Games</h3>
                <p><?php echo $mini_game_count; ?></p>
            </div>
        </div>

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
                <h3>Create Quiz</h3>
                <p>Create quiz questions</p>
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

            <div class="card">
                <h3>Mini Games Upload</h3>
                <p>Create puzzle games for all students</p>
                <a href="mini_game_management.php" class="card-btn">Manage Games</a>
            </div>

            <div class="card">
                <h3>Tearcher Profile</h3>
                <p>View and modify personal information</p>
                <a href="teacher_profile.php" class="card-btn">View</a>
            </div>
        </div>
    </div>

    <script src="../javascriptfile/teacher_main.js"></script>
</body>
</html>