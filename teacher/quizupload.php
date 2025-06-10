<?php 
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';
include 'resheadteacher.php';

// 处理删除请求
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM questions WHERE id = '$delete_id'";
    mysqli_query($connect, $delete_query);
    header("Location: quizupload.php"); // 删除后重定向
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/Teachermain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/upload_quiz.css">
    <title>Quiz Management</title>
    <style>
        /* 新增的样式 */
        .tab-container {
            display: flex;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            margin-right: 5px;
        }
        .tab.active {
            background-color: #4CAF50;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .question-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .question-table th, .question-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .question-table th {
            background-color: #f2f2f2;
        }
        .action-btns {
            display: flex;
            gap: 5px;
        }
        .edit-form {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h2>Quiz Management</h2>
    
    <!-- 选项卡导航 -->
    <div class="tab-container">
        <div class="tab active" onclick="openTab('uploadTab')">Upload Quiz</div>
        <div class="tab" onclick="openTab('manageTab')">Manage Quiz</div>
    </div>
    
    <!-- 上传题目选项卡 -->
    <div id="uploadTab" class="tab-content active">
        <h3>Upload a Question</h3>
        <form id="quizForm" method="POST">
            <div class="form-container">
                <div class="left">
                    <label for="question">Question:</label><br>
                    <textarea id="question" name="question" required></textarea><br><br>
                </div>
                <div class="right">
                    <label for="level">Level:</label>
                    <select id="level" name="level" required>
                        <script>
                            for (let i = 1; i <= 99; i++) {
                                document.write(`<option value="${i}">${i}</option>`);
                            }
                        </script>
                    </select><br><br>

                    <label for="option1">Option 1:</label>
                    <input type="text" id="option1" name="option1" required><br><br>

                    <label for="option2">Option 2:</label>
                    <input type="text" id="option2" name="option2" required><br><br>

                    <label for="option3">Option 3:</label>
                    <input type="text" id="option3" name="option3" required><br><br>

                    <label for="option4">Option 4:</label>
                    <input type="text" id="option4" name="option4" required><br><br>

                    <label for="answer">Correct Answer:</label>
                    <select id="answer" name="answer" required>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                        <option value="4">Option 4</option>
                    </select><br><br>

                    <button type="submit" name="savebtn" class="submit-btn">Submit</button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- 管理题目选项卡 -->
    <div id="manageTab" class="tab-content">
        <h3>Manage Questions</h3>
        
        <table class="question-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Level</th>
                    <th>Options</th>
                    <th>Correct</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM questions ORDER BY difficult ASC";
                $result = mysqli_query($connect, $query);
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['question']}</td>
                        <td>{$row['difficult']}</td>
                        <td>
                            1: {$row['option1']}<br>
                            2: {$row['option2']}<br>
                            3: {$row['option3']}<br>
                            4: {$row['option4']}
                        </td>
                        <td>Option {$row['answer']}</td>
                        <td class='action-btns'>
                            <button onclick=\"editQuestion({$row['id']})\">Edit</button>
                            <button onclick=\"deleteQuestion({$row['id']})\">Delete</button>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        
        <!-- 编辑表单 (初始隐藏) -->
        <div id="editForm" class="edit-form">
            <h3>Edit Question</h3>
            <form id="editQuizForm" method="POST" action="quizupload.php">
                <input type="hidden" id="edit_id" name="edit_id">
                <div class="form-container">
                    <div class="left">
                        <label for="edit_question">Question:</label><br>
                        <textarea id="edit_question" name="edit_question" required></textarea><br><br>
                    </div>
                    <div class="right">
                        <label for="edit_level">Level:</label>
                        <select id="edit_level" name="edit_level" required>
                            <script>
                                for (let i = 1; i <= 99; i++) {
                                    document.write(`<option value="${i}">${i}</option>`);
                                }
                            </script>
                        </select><br><br>

                        <label for="edit_option1">Option 1:</label>
                        <input type="text" id="edit_option1" name="edit_option1" required><br><br>

                        <label for="edit_option2">Option 2:</label>
                        <input type="text" id="edit_option2" name="edit_option2" required><br><br>

                        <label for="edit_option3">Option 3:</label>
                        <input type="text" id="edit_option3" name="edit_option3" required><br><br>

                        <label for="edit_option4">Option 4:</label>
                        <input type="text" id="edit_option4" name="edit_option4" required><br><br>

                        <label for="edit_answer">Correct Answer:</label>
                        <select id="edit_answer" name="edit_answer" required>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                        </select><br><br>

                        <button type="submit" name="updatebtn" class="submit-btn">Update</button>
                        <button type="button" onclick="cancelEdit()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 成功提示弹窗 -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <h2>Operation Successful!</h2>
            <p id="popup-message">Your operation has been completed.</p>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <?php
    // 处理表单提交
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['savebtn'])) {
            // 处理新增题目
            $question = $_POST['question'];
            $level = $_POST['level'];
            $option1 = $_POST['option1'];
            $option2 = $_POST['option2'];
            $option3 = $_POST['option3'];
            $option4 = $_POST['option4'];
            $answer = $_POST['answer'];

            $query = "INSERT INTO questions (question, difficult, option1, option2, option3, option4, answer) 
                     VALUES ('$question', '$level', '$option1', '$option2', '$option3', '$option4', '$answer')";

            if (mysqli_query($connect, $query)) {
                echo "<script>showPopup('Question added successfully!');</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($connect) . "');</script>";
            }
        } elseif (isset($_POST['updatebtn'])) {
            // 处理更新题目
            $id = $_POST['edit_id'];
            $question = $_POST['edit_question'];
            $level = $_POST['edit_level'];
            $option1 = $_POST['edit_option1'];
            $option2 = $_POST['edit_option2'];
            $option3 = $_POST['edit_option3'];
            $option4 = $_POST['edit_option4'];
            $answer = $_POST['edit_answer'];

            $query = "UPDATE questions SET 
                     question = '$question', 
                     difficult = '$level', 
                     option1 = '$option1', 
                     option2 = '$option2', 
                     option3 = '$option3', 
                     option4 = '$option4', 
                     answer = '$answer' 
                     WHERE id = '$id'";

            if (mysqli_query($connect, $query)) {
                echo "<script>showPopup('Question updated successfully!');</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($connect) . "');</script>";
            }
        }
    }
    ?>

    <script src="../javascriptfile/upload_quiz.js"></script>
    <script>
        // 选项卡切换功能
        function openTab(tabName) {
            // 隐藏所有选项卡内容
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // 取消所有选项卡的活动状态
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // 显示选中的选项卡内容
            document.getElementById(tabName).classList.add('active');
            
            // 设置选中选项卡的活动状态
            event.currentTarget.classList.add('active');
        }
        
        // 删除题目
        function deleteQuestion(id) {
            if (confirm('Are you sure you want to delete this question?')) {
                window.location.href = 'quizupload.php?delete_id=' + id;
            }
        }
        
        // 编辑题目
        function editQuestion(id) {
            // 使用AJAX获取题目数据
            fetch('get_question.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    // 填充编辑表单
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_question').value = data.question;
                    document.getElementById('edit_level').value = data.difficult;
                    document.getElementById('edit_option1').value = data.option1;
                    document.getElementById('edit_option2').value = data.option2;
                    document.getElementById('edit_option3').value = data.option3;
                    document.getElementById('edit_option4').value = data.option4;
                    document.getElementById('edit_answer').value = data.answer;
                    
                    // 显示编辑表单
                    document.getElementById('editForm').style.display = 'block';
                    
                    // 滚动到编辑表单
                    document.getElementById('editForm').scrollIntoView();
                });
        }
        
        // 取消编辑
        function cancelEdit() {
            document.getElementById('editForm').style.display = 'none';
        }
        
        // 显示弹窗(修改为可接受消息)
        function showPopup(message) {
            document.getElementById('popup-message').textContent = message;
            document.getElementById('popup').style.display = 'flex';
        }
    </script>
</body>
</html>