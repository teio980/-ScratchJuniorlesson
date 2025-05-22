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
        function openRatingModal(submit_id, student_id, lesson_id) {
            // 获取评分标准
            fetch('../phpfile/get_lesson_criteria.php?lesson_id=' + lesson_id)
                .then(response => response.json())
                .then(data => {
                    const modal = document.getElementById('ratingModal');
                    const form = modal.querySelector('form');
                    
                    // 清空现有评分项
                    form.querySelector('#criteriaContainer').innerHTML = '';
                    
                    // 添加动态评分项
                    let totalMaxScore = 0;
                    const criteria = data.grading_criteria.split('|');
                    
                    criteria.forEach((item, index) => {
                        const [name, maxScore] = item.split(':');
                        totalMaxScore += parseInt(maxScore);
                        
                        const div = document.createElement('div');
                        div.className = 'criteria-item';
                        div.innerHTML = `
                            <label for="criteria_${index}">${name} (0-${maxScore}):</label><br>
                            <input type="number" name="criteria[]" id="criteria_${index}" 
                                   min="0" max="${maxScore}" value="0" 
                                   onchange="calculateTotalScore(${maxScore}, this)">
                            <span class="max-score">/ ${maxScore}</span><br><br>
                        `;
                        form.querySelector('#criteriaContainer').appendChild(div);
                    });
                    
                    // 添加总分显示
                    form.querySelector('#criteriaContainer').innerHTML += `
                        <div class="total-score">
                            <strong>Total Score: </strong>
                            <span id="displayTotal">0</span> / ${totalMaxScore}
                            <input type="hidden" name="total_score" id="total_score" value="0">
                        </div>
                    `;
                    
                    // 设置隐藏字段
                    document.getElementById('submit_id').value = submit_id;
                    document.getElementById('student_id').value = student_id;
                    document.getElementById('lesson_id').value = lesson_id;
                    
                    // 显示模态框
                    modal.style.display = 'block';
                });
        }

        function calculateTotalScore(maxScore, inputElement) {
            const inputs = document.querySelectorAll('input[name="criteria[]"]');
            let total = 0;
            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            
            // 计算百分比分数（最高100分）
            const totalMaxScore = document.querySelector('.total-score').textContent.split('/')[1].trim();
            const percentageScore = Math.round((total / parseInt(totalMaxScore)) * 100);
            const finalScore = Math.min(percentageScore, 100); // 确保不超过100%
            
            document.getElementById('displayTotal').textContent = total;
            document.getElementById('total_score').value = finalScore;
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
                <th>Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // 获取老师负责的所有班级
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
                SELECT ss.*, s.S_username, s.student_id, ss.lesson_id, cw.expire_date, 
                       sc.class_id, l.grading_criteria
                FROM student_submit ss
                JOIN student s ON ss.student_id = s.student_id
                JOIN student_class sc ON s.student_id = sc.student_id
                JOIN class_work cw ON ss.lesson_id = cw.lesson_id
                JOIN lessons l ON ss.lesson_id = l.lesson_id
                WHERE sc.class_id IN ($class_id_list)
                ORDER BY sc.class_id, ss.upload_time DESC
            ";

            $result = mysqli_query($connect, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $current_time = date('Y-m-d H:i:s');
                    $is_within_deadline = $current_time <= $row['expire_date'];
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
                    <a href="../Student/<?= $row['filepath'] ?>" download>Download</a>
                </td>

                <td>
                    <?php if ($is_within_deadline): ?>
                        <button onclick="openRatingModal('<?= $row['submit_id'] ?>', '<?= $row['student_id'] ?>', '<?= $row['lesson_id'] ?>')">
                            <?= $row['score'] !== null ? 'Edit' : 'Rate' ?>
                        </button>
                    <?php else: ?>
                        <span>Rating closed</span>
                    <?php endif; ?>
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

    <button onclick="location.href='Main_page.php'">Back to Dashboard</button>

    <div id="ratingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0, 0, 0, 0.5);">
        <div style="background-color:white; margin:10% auto; padding:20px; width:500px; border-radius:10px;">
            <h3>Rate Submission</h3>
            <form action="../phpfile/save_score.php" method="POST" id="ratingForm">
                <input type="hidden" name="submit_id" id="submit_id">
                <input type="hidden" name="student_id" id="student_id">
                <input type="hidden" name="lesson_id" id="lesson_id">
                <input type="hidden" name="teacher_id" value="<?= $teacher_id ?>">
                
                <div id="criteriaContainer">
                    <!-- 动态生成的评分标准将显示在这里 -->
                </div>
                
                <div id="finalScoreContainer" style="display:none; margin-top:15px;">
                    <strong>Final Score: </strong>
                    <span id="finalScoreDisplay">0</span>%
                </div>
                
                <button type="submit">Save Score</button>
                <button type="button" onclick="closeRatingModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        // 计算总分并显示百分比
        function calculateTotalScore(maxScore, inputElement) {
            const inputs = document.querySelectorAll('input[name="criteria[]"]');
            let total = 0;
            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            
            const totalMaxElement = document.querySelector('.total-score');
            if (totalMaxElement) {
                const totalMaxScore = parseInt(totalMaxElement.textContent.split('/')[1].trim());
                const percentageScore = Math.round((total / totalMaxScore) * 100);
                const finalScore = Math.min(percentageScore, 100);
                
                document.getElementById('displayTotal').textContent = total;
                document.getElementById('total_score').value = finalScore;
                
                // 显示最终分数
                document.getElementById('finalScoreDisplay').textContent = finalScore;
                document.getElementById('finalScoreContainer').style.display = 'block';
            }
        }
    </script>
</body>
</html>