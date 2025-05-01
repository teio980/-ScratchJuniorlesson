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
    <link rel="stylesheet" href="../cssfile/upload_quiz.css">
    <title>Upload Quiz</title>
</head>
<body>
    <h2>Upload a Question</h2>
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

    <div id="popup" class="popup">
        <div class="popup-content">
            <h2>Quiz Uploaded Successfully!</h2>
            <p>Your question has been saved.</p>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <button onclick="location.href='Main_page.php'">Back to Dashboard</button>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $question = $_POST['question'];
        $level = $_POST['level'];
        $option1 = $_POST['option1'];
        $option2 = $_POST['option2'];
        $option3 = $_POST['option3'];
        $option4 = $_POST['option4'];
        $answer = $_POST['answer'];

        $query = "INSERT INTO questions (question, difficult, option1, option2, option3, option4, answer) VALUES ('$question', '$level', '$option1', '$option2', '$option3', '$option4', '$answer')";

        if (mysqli_query($connect, $query)) {
            echo "Quiz saved successfully!";
        } else {
            echo "Error: " . mysqli_error($connect);
        }
    }
    ?>

    <script src="../javascriptfile/upload_quiz.js"></script>    
</body>
</html>
