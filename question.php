<?php
session_start(); 
include 'includes/db.php'; 

$level = $_GET['level']; 

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM questions WHERE level = :level");
$stmt->execute(['level' => $level]);
$totalQuestions = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

if ($totalQuestions < 10) {
    echo "<h1>Not enough questions to start answering!</h1>";
    echo "<p>There are less than 10 questions of the current difficulty ($level).</p>";
    echo "<a href='Course.php'>Back to Course</a>";
    exit();
}

if (!isset($_SESSION['questions'])) {
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE level = :level ORDER BY RAND() LIMIT 10");
    $stmt->execute(['level' => $level]);
    $_SESSION['questions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['current_question'] = 0; 
    $_SESSION['score'] = 0; 
}

$questions = $_SESSION['questions']; 
$currentQuestionIndex = $_SESSION['current_question'];

if ($currentQuestionIndex >= count($questions)) {
    $totalScore = $_SESSION['score']; 
    session_destroy(); 
    echo "<h1>Quiz Completed!</h1>";
    echo "<p>Your total score is: $totalScore / 10</p>";
    echo "<a href='Course.php'>Back to Course</a>";
    exit();
}

$currentQuestion = $questions[$currentQuestionIndex];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question <?php echo $currentQuestionIndex + 1; ?></title>
    <link rel="stylesheet" href="">
</head>
<body>
    <div class="question-container">
        <h1>Question <?php echo $currentQuestionIndex + 1; ?></h1>
        <p><?php echo $currentQuestion['question']; ?></p>
        <form id="answer-form">
            <label>
                <input type="radio" name="answer" value="A">
                <?php echo $currentQuestion['option_a']; ?>
            </label><br>
            <label>
                <input type="radio" name="answer" value="B">
                <?php echo $currentQuestion['option_b']; ?>
            </label><br>
            <label>
                <input type="radio" name="answer" value="C">
                <?php echo $currentQuestion['option_c']; ?>
            </label><br>
            <label>
                <input type="radio" name="answer" value="D">
                <?php echo $currentQuestion['option_d']; ?>
            </label><br>
            <button type="button" onclick="checkAnswer('<?php echo $currentQuestion['correct_answer']; ?>')">Submit</button>
        </form>
    </div>

    <script src="tutorial.js"></script>
</body>
</html>