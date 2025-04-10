<?php
include '../phpfile/connect.php';

$difficulty = $_GET['difficulty'] ?? null;

$sql = "SELECT u.U_Username, COUNT(*) AS correct_answers
        FROM student_answers sa
        JOIN user u ON sa.student_id = u.U_ID
        WHERE sa.is_correct = 1" 
        . ($difficulty ? " AND sa.difficulty = $difficulty" : "") . 
        " GROUP BY u.U_Username
         ORDER BY correct_answers DESC";

$result = $connect->query($sql);
$difficulties = $connect->query("SELECT DISTINCT difficulty FROM student_answers WHERE is_correct = 1");
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
            <button type="submit" name="difficulty" value="<?= $d['difficulty'] ?>">
                Difficulty <?= $d['difficulty'] ?>
            </button>
        <?php endwhile; ?>
    </form>

    <?php if($result->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <tr><th>Username</th><th>Correct</th></tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['U_Username'] ?></td>
                    <td><?= $row['correct_answers'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No results found<?= $difficulty ? " for difficulty $difficulty" : "" ?>.</p>
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