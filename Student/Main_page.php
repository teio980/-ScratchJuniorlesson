<?php
session_start();
include '../phpfile/connect.php'; 

$sql = "SELECT lesson_id, title, description,expire_date FROM lessons ORDER BY lesson_id ASC";
$result = $connect->query($sql);

// Fetch quiz questions by difficult
$sql_quiz = "SELECT DISTINCT difficult FROM questions ORDER BY difficult ASC";
$result_quiz = $connect->query($sql_quiz);

include '../reshead.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/reshead.css">
    <link rel="stylesheet" href="cssfile/Main_page.css">
    <title>Profile Page</title>
</head>
<body>

<h2>Available Lessons</h2>
<table border="1">
    <tr>
        <th>Lesson</th> 
        <th>Title</th>
        <th>Description</th>
        <th>Expired date</th>
        <th>Action</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['lesson_id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['expire_date']}</td>
                    <td>
                        <form action='studentsubmit.php' method='GET'>
                            <input type='hidden' name='lesson_id' value='{$row['lesson_id']}'>
                            <button type='submit'>Submit</button>
                        </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No lessons available</td></tr>";
    }?>
    </table>
    <h2>Quiz Levels</h2>
<table border="1">
    <tr>
        <th>Level</th>
        <th>Available</th>
        <th>Percentage</th>
        <th>Action</th>
        
    </tr>
    <?php
    if ($result_quiz->num_rows > 0) {
        while ($row = $result_quiz->fetch_assoc()) {
            echo "<tr>
                    <td>Level {$row['difficult']}</td>
                    <td></td>
                    <td></td>
                    <td>
                        <button>
                            <a href='Questionpaper.php?difficult={$row['difficult']}'>Answer</a>
                        </button>
                    </td>
                    </tr>";
        }
    } else {
        echo "<tr><td colspan='2'>No quiz data available</td></tr>";
    }
    ?>
</table>

</body>
</html>
<?php
$connect->close();
?>
    