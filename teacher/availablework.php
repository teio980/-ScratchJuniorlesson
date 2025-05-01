<?php
include '../phpfile/connect.php';
include '../resheadAfterLogin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <title>Assign Lesson to Class</title>
</head>
<body>
    <h2>Assign Lesson to Class</h2>

    <form method="POST" action="">
        <label>Select Class:</label><br>
        <select name="class_id" required>
            <?php
            $classResult = $connect->query("SELECT class_id FROM class");
            while ($class = $classResult->fetch_assoc()) {
                echo "<option value='" . $class['class_id'] . "'>" . $class['class_id'] . "</option>";
            }
            ?>
        </select>
        <br><br>

        <label>Select Lesson:</label><br>
        <select name="lesson_id" id="lessonSelect" onchange="updateThumbnail()" required>
            <?php
            $lessonResult = $connect->query("SELECT lesson_id, title, lesson_file_name, thumbnail_name FROM lessons");
            while ($lesson = $lessonResult->fetch_assoc()) {
                $lesson_json = htmlspecialchars(json_encode([
                    'lesson_id' => $lesson['lesson_id'],
                    'title' => $lesson['title'],
                    'lesson_file_name' => $lesson['lesson_file_name'],
                    'thumbnail_name' => $lesson['thumbnail_name']
                ]));
                echo "<option value='" . $lesson['lesson_id'] . "' data-lesson='" . $lesson_json . "'>" .
                    $lesson['title'] . " (" . $lesson['lesson_file_name'] . ")</option>";
            }
            ?>
        </select>
        <br><br>

        <label>Preview Thumbnail:</label><br>
        <img id="thumbnailPreview" src="" width="150" alt="Thumbnail will appear here">
        <br><br>

        <label>Set Expire Date:</label><br>
        <input type="datetime-local" name="expire_date" required>
        <br><br>

        <button type="submit" name="assign_submit">Assign Lesson</button>
    </form>

    <script>
    function updateThumbnail() {
        var select = document.getElementById('lessonSelect');
        var selectedOption = select.options[select.selectedIndex];
        var lessonData = JSON.parse(selectedOption.getAttribute('data-lesson'));
        var thumbnailPath = '../phpfile/upload_teacher/' + lessonData.thumbnail_name;
        document.getElementById('thumbnailPreview').src = thumbnailPath;
    }

    window.onload = updateThumbnail;
    </script>

    <button onclick="location.href='Main_page.php'">Back to Dashboard</button>
</body>
</html>

<?php
if (isset($_POST['assign_submit'])) {
    $class_id = $_POST['class_id'];
    $lesson_id = $_POST['lesson_id'];
    $expire_date = $_POST['expire_date'];
    $classwork_id = uniqid("cw_");

    $query = "SELECT lesson_file_name FROM lessons WHERE lesson_id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("s", $lesson_id);
    $stmt->execute();
    $stmt->bind_result($lesson_file_name);

    if ($stmt->fetch()) {
        $stmt->close();

        $sql_T_checkQty = "SELECT COUNT(*) AS total FROM class_work";
        $result = $connect->query($sql_T_checkQty);
        $row = $result->fetch_assoc();
        $classwork_Qty = $row['total'];
        $classwork_id = 'CW' . str_pad($classwork_Qty + 1, 6, '0', STR_PAD_LEFT);

        $insert_query = "INSERT INTO class_work (availability_id, lesson_id, class_id, student_work, expire_date) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $connect->prepare($insert_query);
        $insert_stmt->bind_param("sssss", $classwork_id, $lesson_id ,$class_id, $lesson_file_name, $expire_date);

        if ($insert_stmt->execute()) {
            echo "<script>alert('Lesson assigned successfully.'); window.location.href = 'Main_page.php';</script>";
        } else {
            echo "Failed to assign lesson: " . $insert_stmt->error;
        }

        $insert_stmt->close();
    } else {
        echo "Lesson not found.";
    }

    $connect->close();
}
?>
