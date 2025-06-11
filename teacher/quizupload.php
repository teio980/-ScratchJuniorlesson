<?php 
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';
include 'resheadteacher.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['savebtn'])) {
    $question = mysqli_real_escape_string($connect, $_POST['question']);
    $level = intval($_POST['level']);
    $option1 = mysqli_real_escape_string($connect, $_POST['option1']);
    $option2 = mysqli_real_escape_string($connect, $_POST['option2']);
    $option3 = mysqli_real_escape_string($connect, $_POST['option3']);
    $option4 = mysqli_real_escape_string($connect, $_POST['option4']);
    $answer = intval($_POST['answer']);

    $query = "INSERT INTO questions (question, difficult, option1, option2, option3, option4, answer) 
             VALUES ('$question', '$level', '$option1', '$option2', '$option3', '$option4', '$answer')";

    if (mysqli_query($connect, $query)) {
        $success_message = "Public question added successfully!";
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
    <title>Public Quiz Management</title>
    <link rel="stylesheet" href="../cssfile/Teachermain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/upload_quiz.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Public Quiz Management</h1>
            <p>Manage questions that will be available to all classes</p>
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

        <div class="action-buttons">
            <button class="button primary-btn" onclick="showTab('upload')">Upload Quiz</button>
            <button class="button primary-btn" onclick="window.location.href='view_quiz.php'">View/Manage Quiz</button>
        </div>

        <div id="upload" class="tab-content active">
            <div class="form-container">
                <h2 class="form-title">Add New Public Question</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="question">Question:</label>
                        <textarea id="question" name="question" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="level">Difficulty Level (1-99):</label>
                        <select id="level" name="level" required>
                            <?php for ($i = 1; $i <= 99; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="option1">Option 1:</label>
                        <input type="text" id="option1" name="option1" required>
                    </div>

                    <div class="form-group">
                        <label for="option2">Option 2:</label>
                        <input type="text" id="option2" name="option2" required>
                    </div>

                    <div class="form-group">
                        <label for="option3">Option 3:</label>
                        <input type="text" id="option3" name="option3" required>
                    </div>

                    <div class="form-group">
                        <label for="option4">Option 4:</label>
                        <input type="text" id="option4" name="option4" required>
                    </div>

                    <div class="form-group">
                        <label for="answer">Correct Answer:</label>
                        <select id="answer" name="answer" required>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                        </select>
                    </div>

                    <button type="submit" name="savebtn" class="submit-btn">Add Public Question</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Only needed if we had multiple tabs on this page
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabName).classList.add('active');
        }
    </script>
</body>
</html>