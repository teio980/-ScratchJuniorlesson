</html>
<?php
session_start();
include '../phpfile/connect.php';

$difficulty = $_GET['difficult'];
$sql = "SELECT * FROM questions WHERE difficult = $difficulty";
$result = mysqli_query($connect, $sql);

include '../resheadAfterLogin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Level <?php echo $difficulty; ?></title>
  <link rel="stylesheet" href="../cssfile/headeraf.css">
  <link rel="stylesheet" href="../cssfile/quizpaper.css"> 
</head>
<body>
  <div class="quizbody">
    <div class="quiz-container">
      <h2 class="quiz-title">Quiz - Level <?php echo $difficulty; ?></h2>

      <div class="progress-bar">
        <div class="progress-bar-fill" id="progressFill"></div>
      </div>

      <form action="submit_answers.php" method="POST" id="quizForm">
        <input type="hidden" name="difficult" value="<?php echo $difficulty; ?>">

        <?php
        if ($result->num_rows > 0) {
            $i = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<div class='question-block' data-qid='{$row['id']}'>";
                echo "<p class='question-text'><b>Q$i: {$row['question']}</b></p>";

                echo "<div class='options'>";
                for ($j = 1; $j <= 4; $j++) {
                    $optionText = htmlspecialchars($row["option$j"]);
                    echo "<button type='button' class='option-btn' data-qid='{$row['id']}' data-value='$j'>$optionText</button>";
                }
                echo "</div>";

                echo "<input type='hidden' name='answer[{$row['id']}]' id='answer_{$row['id']}'>";

                if ($i === (int)$result->num_rows) {
                    echo "<input class='submit-btn' type='submit' value='Submit Answers' name='savebtn' style='display: none;'>";
                }

                echo "</div>";
                $i++;
            }
        }

        $connect->close();
        ?>
      </form>
    </div>
  </div>

  <script>
    const questions = document.querySelectorAll('.question-block');
    const progressBar = document.getElementById('progressFill');
    const submitBtn = document.querySelector('.submit-btn');
    let currentIndex = 0;

    function showQuestion(index) {
      questions.forEach((q, i) => {
        q.style.display = (i === index) ? 'block' : 'none';
      });

      const percent = ((index + 1) / questions.length) * 100;
      progressBar.style.width = percent + '%';
    }

    document.querySelectorAll('.option-btn').forEach(button => {
      button.addEventListener('click', () => {
        const qid = button.dataset.qid;
        const value = button.dataset.value;


        document.getElementById('answer_' + qid).value = value;

        const siblings = button.parentElement.querySelectorAll('.option-btn');
        siblings.forEach(btn => btn.classList.remove('selected'));
        button.classList.add('selected');

        setTimeout(() => {
          currentIndex++;
          if (currentIndex < questions.length) {
            showQuestion(currentIndex);
          } else {
            submitBtn.style.display = 'block';
          }
        }, 300); 
      });
    });

    showQuestion(0);
  </script>
</body>
</html>
