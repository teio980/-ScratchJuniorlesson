<?php
session_start();
include '../phpfile/connect.php';

$teacher_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Submissions</title>
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <link rel="stylesheet" href="../cssfile/view_ssub.css">
    <script src="../javascriptfile/download_all.js"></script>
    <script>
        function openRatingModal(submit_id, student_id) {
            document.getElementById('ratingModal').style.display = 'block';
            document.getElementById('submit_id').value = submit_id;
            document.getElementById('student_id').value = student_id;
        }

        function closeRatingModal() {
            document.getElementById('ratingModal').style.display = 'none';
        }
    </script>
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
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $class_query = "SELECT class_id FROM teacher_class WHERE teacher_id = '$teacher_id'";
        $class_result = mysqli_query($connect, $class_query);

        $class_ids = [];
        while ($row = mysqli_fetch_assoc($class_result)) {
            $class_ids[] = "'" . $row['class_id'] . "'";
        }

        if (!empty($class_ids)) {
            $class_id_list = implode(",", $class_ids);

            $query = "
                SELECT ss.*, s.S_username, s.student_id, ss.lesson_id, cw.expire_date, sc.class_id
                FROM student_submit ss
                JOIN student s ON ss.student_id = s.student_id
                JOIN student_class sc ON s.student_id = sc.student_id
                JOIN class_work cw ON ss.lesson_id = cw.lesson_id
                WHERE sc.class_id IN ($class_id_list)
            ";

            $result = mysqli_query($connect, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $current_time = date('Y-m-d H:i:s');
                    $is_within_deadline = $current_time <= $row['expire_date'];

                    $status_query = "SELECT * FROM ratings WHERE submit_id = '" . $row['submit_id'] . "'";
                    $status_result = mysqli_query($connect, $status_query);
                    $rating_row = mysqli_fetch_assoc($status_result);

                    $total_score = isset($rating_row['rating']) ? $rating_row['rating'] : 0;
                    $has_rating = $rating_row !== null;
                    $status = $total_score >= 50 ? '✅' : '❌';
        ?>
                    <tr>
                        <td><?php echo $row['submit_id']; ?></td>
                        <td><?php echo $row['S_username']; ?></td>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo $row['lesson_id']; ?></td>
                        <td><?php echo $row['filename']; ?></td>
                        <td><?php echo $row['class_id']; ?></td>
                        <td>
                            <a href="../Student/<?php echo $row['filepath']; ?>" class="download-link" data-filename="<?php echo $row['filename']; ?>" download>Download</a>
                        </td>
                        <td>
                            <?php if ($is_within_deadline): ?>
                                <button onclick="openRatingModal('<?php echo $row['submit_id']; ?>', '<?php echo $row['student_id']; ?>')">Rate</button>
                            <?php else: ?>
                                <span>Rating closed</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $has_rating ? $status : ''; ?></td>
                    </tr>
        <?php
                }
            } else {
                echo "<tr><td colspan='9'>No uploads found for your classes.</td></tr>";
            }
        } else {
            echo "<tr><td colspan='9'>You are not assigned to any classes.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <button onclick="location.href='Main_page.php'">Back to Dashboard</button>

    <div id="ratingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0, 0, 0, 0.5);">
        <div style="background-color:white; margin:10% auto; padding:20px; width:400px; border-radius:10px;">
            <h3>Rate Submission</h3>
            <form action="rate_submission.php" method="POST">
                <input type="hidden" name="submit_id" id="submit_id">
                <input type="hidden" name="student_id" id="student_id">

                <label for="code_quality">Code Quality (0-25):</label><br>
                <input type="number" name="code_quality" id="code_quality" min="0" max="25" required><br><br>

                <label for="problem_solving">Problem Solving (0-25):</label><br>
                <input type="number" name="problem_solving" id="problem_solving" min="0" max="25" required><br><br>

                <label for="creativity">Creativity (0-25):</label><br>
                <input type="number" name="creativity" id="creativity" min="0" max="25" required><br><br>

                <label for="presentation">Presentation (0-25):</label><br>
                <input type="number" name="presentation" id="presentation" min="0" max="25" required><br><br>

                <button type="submit">Submit Rating</button>
                <button type="button" onclick="closeRatingModal()">Close</button>
            </form>
        </div>
    </div>
</body>
</html>
