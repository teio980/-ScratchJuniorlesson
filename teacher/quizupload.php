<?php 
session_start();
include '../phpfile/connect.php'; 
include '../resheadAfterLogin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <title>Upload Quiz</title>
</head>
<body>
    <h2>Upload a Question</h2>
    <form method="POST">
        <label for="question">Question:</label><br>
        <textarea id="question" name="question" rows="4" cols="50" required></textarea><br><br>
        
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
        
        <input type="submit" name="savebtn" value="Submit">
    </form>
</body>
</html>

<?php
    if (isset($_POST['savebtn'])) {
        $question = $_POST['question'];
        $level = $_POST['level'];
        $option1 = $_POST['option1'];
        $option2 = $_POST['option2'];
        $option3 = $_POST['option3'];
        $option4 = $_POST['option4'];
        $answer = $_POST['answer'];

        mysqli_query($connect,"INSERT INTO questions (question, difficult, option1, option2, option3, option4, answer) VALUES ('$question', '$level', '$option1', '$option2', '$option3', '$option4', '$answer')");
        
        ?>
        
            <script type="text/javascript">
                alert('<?php echo "Quiz saved"?>');
                window.location.href = 'quizupload.php'; 
            </script>
        
        <?php
        
    }
    
?>