<?php
session_start();
include '../phpfile/connect.php';

if (!isset($_GET['difficult']) || !is_numeric($_GET['difficult'])) {
    echo "Invalid difficulty level.";
    exit;
}

$difficulty = intval($_GET['difficult']);

$sql = "SELECT * FROM questions WHERE difficult = $difficulty";
$result = $connect->query($sql);

include '../resheadAfterLogin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/reshead.css">
    <link rel="stylesheet" href="../cssfile/Main_page.css">
    <title>Level <?php echo $difficulty; ?></title>
</head>
<body>

<h2>Question Paper - Level <?php echo $difficulty; ?></h2>

<?php
if ($result->num_rows > 0) {
    echo "<form action='submit_answers.php' method='POST'>";
    echo "<input type='hidden' name='difficult' value='$difficulty'>";
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<p><b>Q$i: {$row['question']}</b></p>";
        echo "<input type='radio' name='answer[$row[id]]' value='1' required> {$row['option1']}<br>";
        echo "<input type='radio' name='answer[$row[id]]' value='2'> {$row['option2']}<br>";
        echo "<input type='radio' name='answer[$row[id]]' value='3'> {$row['option3']}<br>";
        echo "<input type='radio' name='answer[$row[id]]' value='4'> {$row['option4']}<br><br>";
        $i++;
    }
    echo "<input type='submit' value='Submit Answers'>";
    echo "</form>";
}
$connect->close();
?>

</body>
</html>
