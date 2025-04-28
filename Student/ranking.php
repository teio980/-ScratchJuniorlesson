<?php
include '../phpfile/connect.php';

$difficulty = $_GET['difficult'];

$sql = "SELECT student.S_Username, student_class.class_id, COUNT(*) AS correct_answers
        FROM student_answers
        JOIN student ON student_answers.student_id = student.student_id
        JOIN student_class ON student.student_id = student_class.student_id
        WHERE student_answers.is_correct = 1"
        . ($difficulty ? " AND student_answers.difficult = '$difficulty'" : "") . 
        " GROUP BY student.S_Username, student_class.class_id
          ORDER BY correct_answers DESC";

$result = $connect->query($sql);

$difficulties = $connect->query("SELECT DISTINCT difficult FROM student_answers WHERE is_correct = 1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ranking Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../cssfile/rank.css" />
</head>
<body>

  <div class="ranking-wrapper">
    <div class="medals">
      <div class="medal silver">
        <div class="emoji">🥈</div>
        <p id="silver-medal-name">--</p>
      </div>
      <div class="medal gold">
        <div class="emoji">🥇</div>
        <p id="gold-medal-name">--</p>
      </div>
      <div class="medal bronze">
        <div class="emoji">🥉</div>
        <p id="bronze-medal-name">--</p>
      </div>
    </div>

    <div class="ranking-icon">
      <i class="fa-solid fa-ranking-star"></i>
    </div>

    <div class="leaderboard-section">
      <div class="quiz-select">
        <div class="custom-select">
          <form method="get">
            <select name="difficult" onchange="this.form.submit()">
              <option value="">Total</option>
              <?php while ($d = $difficulties->fetch_assoc()): ?>
                <option value="<?= $d['difficult'] ?>" <?= $difficulty == $d['difficult'] ? 'selected' : '' ?>>
                  Quiz <?= $d['difficult'] ?>
                </option>
              <?php endwhile; ?>
            </select>
          </form>
        </div>
      </div>

      <!-- Table Container -->
      <div class="table-container">
        <?php if($result->num_rows > 0): ?>
          <table id="ranking-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Name</th>
                <th>Class</th>
                <th>Correct Answers</th>
              </tr>
            </thead>
            <tbody>
              <?php $rank = 1; ?>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $rank++ ?></td>
                  <td><?= htmlspecialchars($row['S_Username']) ?></td>
                  <td><?= htmlspecialchars($row['class_id']) ?></td>
                  <td><?= htmlspecialchars($row['correct_answers']) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>

        <?php else: ?>
          <p>No results found<?= $difficulty ? " for difficulty '$difficulty'" : "" ?>.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>


  <a href="Main_page.php" class="floating-return-button">← Return</a>



  <script>
    window.onload = function() {
      const rows = document.querySelectorAll('#ranking-table tbody tr');

      const sortedRows = Array.from(rows).sort((a, b) => {
        const correctAnswersA = parseInt(a.cells[3].textContent);
        const correctAnswersB = parseInt(b.cells[3].textContent);
        return correctAnswersB - correctAnswersA; 
      });


      document.getElementById('gold-medal-name').textContent = sortedRows[0]?.cells[1].textContent || '--';  // First place: Gold
      document.getElementById('silver-medal-name').textContent = sortedRows[1]?.cells[1].textContent || '--'; // Second place: Silver
      document.getElementById('bronze-medal-name').textContent = sortedRows[2]?.cells[1].textContent || '--'; // Third place: Bronze
    };
  </script>

</body>
</html>

<?php
$connect->close();
?>
