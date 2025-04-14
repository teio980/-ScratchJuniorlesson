<?php
include '../phpfile/connect.php';

$difficulty = $_GET['difficulty'] ?? null;

$sql = "SELECT student.S_Username, COUNT(*) AS correct_answers
        FROM student_answers
        JOIN student ON student_answers.student_id = student.student_id
        WHERE student_answers.is_correct = 1" 
        . ($difficulty ? " AND student_answers.difficult = '$difficulty'" : "") . 
        " GROUP BY student.S_Username
          ORDER BY correct_answers DESC";

$result = $connect->query($sql);
$difficulties = $connect->query("SELECT DISTINCT difficult FROM student_answers WHERE is_correct = 1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking</title>
</head>
<body>
    <h2>Rankings</h2>
    
    <form method="get">
        <?php while($d = $difficulties->fetch_assoc()): ?>
            <button type="submit" name="difficult" value="<?= $d['difficult'] ?>">
                Quiz <?= $d['difficult'] ?>
            </button>
        <?php endwhile; ?>
    </form>

    <?php if($result->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <tr><th>Username</th><th>Correct</th></tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['S_Username'] ?></td>
                    <td><?= $row['correct_answers'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No results found<?= $difficulty ? " for difficult $difficulty" : "" ?>.</p>
    <?php endif; ?>

    <a href="Main_page.php">
        <button type="button">Return</button>
    </a>
</body>
</html>

<script>
    window.lo
</script>

<?php $connect->close(); ?>