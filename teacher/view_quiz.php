<?php 
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';
include 'resheadteacher.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM questions WHERE id = '$delete_id'";
    mysqli_query($connect, $delete_query);
    header("Location: view_quiz.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatebtn'])) {
    $id = intval($_POST['edit_id']);
    $question = mysqli_real_escape_string($connect, $_POST['edit_question']);
    $level = intval($_POST['edit_level']);
    $option1 = mysqli_real_escape_string($connect, $_POST['edit_option1']);
    $option2 = mysqli_real_escape_string($connect, $_POST['edit_option2']);
    $option3 = mysqli_real_escape_string($connect, $_POST['edit_option3']);
    $option4 = mysqli_real_escape_string($connect, $_POST['edit_option4']);
    $answer = intval($_POST['edit_answer']);

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
        $success_message = "Question updated successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View/Manage Quiz</title>
    <link rel="stylesheet" href="../cssfile/Teachermain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/upload_quiz.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>View/Manage Public Questions</h1>
            <button class="button primary-btn" onclick="window.location.href='quizupload.php'">Back to Upload</button>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="notification success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="notification error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

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
                            <strong>1:</strong> {$row['option1']}<br>
                            <strong>2:</strong> {$row['option2']}<br>
                            <strong>3:</strong> {$row['option3']}<br>
                            <strong>4:</strong> {$row['option4']}
                        </td>
                        <td>Option {$row['answer']}</td>
                        <td class='action-btns'>
                            <button class='button primary-btn' onclick=\"editQuestion({$row['id']})\">Edit</button>
                            <button class='button primary-btn' onclick=\"deleteQuestion({$row['id']})\">Delete</button>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h2>Edit Question</h2>
                <form id="editForm" method="POST">
                    <input type="hidden" id="edit_id" name="edit_id">
                    <div class="form-group">
                        <label>Question:</label>
                        <textarea id="edit_question" name="edit_question" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Difficulty Level:</label>
                        <select id="edit_level" name="edit_level" required>
                            <?php for ($i = 1; $i <= 99; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Option 1:</label>
                        <input type="text" id="edit_option1" name="edit_option1" required>
                    </div>
                    <div class="form-group">
                        <label>Option 2:</label>
                        <input type="text" id="edit_option2" name="edit_option2" required>
                    </div>
                    <div class="form-group">
                        <label>Option 3:</label>
                        <input type="text" id="edit_option3" name="edit_option3" required>
                    </div>
                    <div class="form-group">
                        <label>Option 4:</label>
                        <input type="text" id="edit_option4" name="edit_option4" required>
                    </div>
                    <div class="form-group">
                        <label>Correct Answer:</label>
                        <select id="edit_answer" name="edit_answer" required>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                        </select>
                    </div>
                    <button type="submit" name="updatebtn" class="submit-btn">Update Question</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function deleteQuestion(id) {
            if (confirm('Are you sure you want to delete this public question?')) {
                window.location.href = 'view_quiz.php?delete_id=' + id;
            }
        }
        
        function editQuestion(id) {
            fetch('get_question.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_question').value = data.question;
                    document.getElementById('edit_level').value = data.difficult;
                    document.getElementById('edit_option1').value = data.option1;
                    document.getElementById('edit_option2').value = data.option2;
                    document.getElementById('edit_option3').value = data.option3;
                    document.getElementById('edit_option4').value = data.option4;
                    document.getElementById('edit_answer').value = data.answer;
                    
                    document.getElementById('editModal').style.display = 'block';
                });
        }
        
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>