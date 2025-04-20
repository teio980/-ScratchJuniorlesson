<?php
include '../phpfile/connect.php';

// Get the selected difficulty from the GET request, if any.
$difficulty = $_GET['difficult'] ?? null;

// Modify the SQL query to get the ranking data for the table below
$sql = "SELECT student.S_Username, student_class.class_id, COUNT(*) AS correct_answers
        FROM student_answers
        JOIN student ON student_answers.student_id = student.student_id
        JOIN student_class ON student.student_id = student_class.student_id
        WHERE student_answers.is_correct = 1"
        . ($difficulty ? " AND student_answers.difficult = '$difficulty'" : "") . 
        " GROUP BY student.S_Username, student_class.class_id
          ORDER BY correct_answers DESC";

// Execute the query to get the ranking data.
$result = $connect->query($sql);

// Get the distinct difficulties available in the database.
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
    <!-- Medal Display (Initially empty) -->
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

    <!-- Ranking Icon -->
    <div class="ranking-icon">
      <i class="fa-solid fa-ranking-star"></i>
    </div>

    <!-- Leaderboard Section -->
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
        <button class="returnbutton"><a href="Main_page.php">Return</a></button>
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

  <script>
    // Wait for the table to load before processing it
    window.onload = function() {
      // Select all table rows
      const rows = document.querySelectorAll('#ranking-table tbody tr');

      // Create an array of the rows with the number of correct answers and sort them
      const sortedRows = Array.from(rows).sort((a, b) => {
        const correctAnswersA = parseInt(a.cells[3].textContent);
        const correctAnswersB = parseInt(b.cells[3].textContent);
        return correctAnswersB - correctAnswersA; // Sort in descending order
      });

      // Update the medals section based on the sorted rows
      document.getElementById('gold-medal-name').textContent = sortedRows[0]?.cells[1].textContent || '--';  // First place: Gold
      document.getElementById('silver-medal-name').textContent = sortedRows[1]?.cells[1].textContent || '--'; // Second place: Silver
      document.getElementById('bronze-medal-name').textContent = sortedRows[2]?.cells[1].textContent || '--'; // Third place: Bronze
    };
  </script>

</body>
</html>

<?php
// Close the database connection
$connect->close();
?>
