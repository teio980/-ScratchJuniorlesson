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
    <title>Student Submissions</title>
    <link rel="stylesheet" href="../cssfile/view_ssub.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
</head>
<body>
    <h2>View Class Submissions</h2>
    <button onclick="downloadAll()">Download All Lessons</button><br><br>

    <table border="1">
        <thead>
            <tr>
                <th>Submit ID</th>
                <th>Name</th>
                <th>Student ID</th>
                <th>Lesson ID</th>
                <th>Filename</th>
                <th>Class ID</th>
                <th>Download</th>
                <th>Rating</th>
                <th>Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $class_query = "SELECT tc.class_id, c.class_name 
                       FROM teacher_class tc
                       JOIN class c ON tc.class_id = c.class_id
                       WHERE tc.teacher_id = '$teacher_id'";
        $class_result = mysqli_query($connect, $class_query);

        $class_ids = [];
        $class_names = [];
        while ($row = mysqli_fetch_assoc($class_result)) {
            $class_ids[] = "'" . $row['class_id'] . "'";
            $class_names[$row['class_id']] = $row['class_name'];
        }

        if (!empty($class_ids)) {
            $class_id_list = implode(",", $class_ids);

            $query = "
                SELECT ss.*, s.S_username, s.student_id, ss.lesson_id, 
                       sc.class_id, l.grading_criteria
                FROM student_submit ss
                JOIN student s ON ss.student_id = s.student_id
                JOIN student_class sc ON s.student_id = sc.student_id
                JOIN lessons l ON ss.lesson_id = l.lesson_id
                WHERE sc.class_id IN ($class_id_list)
                ORDER BY sc.class_id, ss.upload_time DESC
            ";

            $result = mysqli_query($connect, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $class_name = $class_names[$row['class_id']] ?? $row['class_id'];
        ?>
            <tr>
                <td><?= $row['submit_id'] ?></td>
                <td><?= $row['S_username'] ?></td>
                <td><?= $row['student_id'] ?></td>
                <td><?= $row['lesson_id'] ?></td>
                <td><?= $row['filename'] ?></td>
                <td><?= $class_name ?></td>
                
                <td>
                    <a href="../Student/uploads/<?= $row['student_id'] ?>/<?= $row['filename'] ?>" download>Download</a>
                </td>

                <td>
                    <button onclick="openRatingModal('<?= $row['submit_id'] ?>', '<?= $row['student_id'] ?>', '<?= $row['lesson_id'] ?>')">
                        <?= $row['score'] !== null ? 'Edit' : 'Rate' ?>
                    </button>
                </td>

                <td><?= $row['score'] !== null ? $row['score'] . '%' : 'Not graded' ?></td>

                <td>
                    <?php 
                    if ($row['score'] === null) {
                        echo 'Not graded';
                    } else {
                        echo $row['score'] >= 40 ? '✅ Pass' : '❌ Fail';
                    }
                    ?>
                </td>
            </tr>
        <?php
                }
            } else {
                echo "<tr><td colspan='10'>No submissions found for your classes.</td></tr>";
            }
        } else {
            echo "<tr><td colspan='10'>You are not assigned to any classes.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <div id="ratingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0, 0, 0, 0.5);">
        <div style="background-color:white; margin:10% auto; padding:20px; width:500px; border-radius:10px;">
            <h3>Rate Submission</h3>
            <form action="../phpfile/save_score.php" method="POST" id="ratingForm">
                <input type="hidden" name="submit_id" id="submit_id">
                <input type="hidden" name="student_id" id="student_id">
                <input type="hidden" name="lesson_id" id="lesson_id">
                <input type="hidden" name="teacher_id" value="<?= $teacher_id ?>">
                
                <div id="criteriaContainer">
                </div>
                
                <div id="finalScoreContainer" style="display:none; margin-top:15px;">
                    <strong>Final Score: </strong>
                    <span id="finalScoreDisplay">0</span>%
                </div>

                <div style="margin-top: 15px;">
                    <label for="feedback"><strong>Feedback/Comments:</strong></label><br>
                    <textarea name="feedback" id="feedback" rows="4" style="width: 100%;"></textarea>
                </div>
                
                <button type="submit">Save Score</button>
                <button type="button" onclick="closeRatingModal()">Cancel</button>
            </form>
        </div>
    </div>
    
    <script src="../javascriptfile/download_all.js"></script>
    <script src="../javascriptfile/view_submissions.js"></script>
</body>
</html>