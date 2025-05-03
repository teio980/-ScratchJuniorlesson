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
<<<<<<< HEAD
=======
    <link rel="stylesheet" href="../cssfile/Tmain.css">
>>>>>>> origin/main
    <title>Teacher</title>
</head>
<body>
    <h1>Welcome, Teacher</h1>

    <div class="teacher-options">
        <button onclick="location.href='lesson_management.php'">Lesson Management</button>
        <button onclick="location.href='view_submissions.php'">View Student Submissions</button>
        <button onclick="location.href='quizupload.php'">Upload Quiz Questions</button>
        <button onclick="location.href='availablework.php'">Work for student</button>
    </div>

<<<<<<< HEAD
    <h2 class="section-title">📘 Uploaded Lessons</h2>

<?php
if (isset($_POST['update'])) {
    $lesson_id = $_POST['lesson_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $connect->prepare("SELECT lesson_file_name, thumbnail_name FROM lessons WHERE lesson_id = ?");
    $stmt->bind_param("s", $lesson_id);
    $stmt->execute();
    $stmt->bind_result($old_file, $old_thumb);
    $stmt->fetch();
    $stmt->close();

    $lesson_file_name = $old_file;
    $thumbnail_name = $old_thumb;

    if ($_FILES['lesson_file']['name'] !== '') {
        $lesson_file_name = $_FILES['lesson_file']['name'];
        $target = "../phpfile/uploads_teacher/" . $lesson_file_name;
        if (file_exists("../phpfile/uploads_teacher/" . $old_file)) {
            unlink("../phpfile/uploads_teacher/" . $old_file);
        }
        move_uploaded_file($_FILES['lesson_file']['tmp_name'], $target);
    }

    if ($_FILES['thumbnail']['name'] !== '') {
        $thumbnail_name = $_FILES['thumbnail']['name'];
        $thumb_target = "../phpfile/uploads/thumbnail/" . $thumbnail_name;
        if (file_exists("../phpfile/uploads/thumbnail/" . $old_thumb)) {
            unlink("../phpfile/uploads/thumbnail/" . $old_thumb);
        }
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumb_target);
    }

    $stmt = $connect->prepare("UPDATE lessons SET title = ?, description = ?, lesson_file_name = ?, thumbnail_name = ? WHERE lesson_id = ?");
    $stmt->bind_param("sssss", $title, $description, $lesson_file_name, $thumbnail_name, $lesson_id); 
    $stmt->execute();
    $stmt->close();

    header("Location: Main_page.php");
    exit();
}

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id']; 
}

if (isset($_GET['id'])) {
    $lesson_id = $_GET['id']; 

    $stmt = $connect->prepare("SELECT lesson_file_name, thumbnail_name FROM lessons WHERE lesson_id = ?");

    $stmt->bind_param("s", $lesson_id); 
    $stmt->execute();

    $stmt->bind_result($fileName, $thumbnail);
    if ($stmt->fetch()) {
        $file_path = "../phpfile/uploads_teacher/" . $fileName;
        $thumbnail_path = "../phpfile/uploads/thumbnail/" . $thumbnail;
        if (file_exists($file_path)) unlink($file_path);
        if (file_exists($thumbnail_path)) unlink($thumbnail_path);
    }
    $stmt->close();

    $stmt = $connect->prepare("DELETE FROM lessons WHERE lesson_id = ?");
    $stmt->bind_param("s", $lesson_id); 
    $stmt->execute();
    $stmt->close();

    header("Location: Main_page.php");
    exit();
}

$sql = "SELECT lesson_id, title, description, lesson_file_name, thumbnail_name, create_time FROM lessons";
$result = $connect->query($sql);

echo "<table border='1'>
<tr>
    <th>Title</th>
    <th>Description</th>
    <th>Lesson File</th>
    <th>Thumbnail</th>
    <th>Create Time</th>
    <th>Actions</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    if (isset($edit_id) && $edit_id == $row['lesson_id']) {
        echo "<form method='post' enctype='multipart/form-data'>
              <td><input type='text' name='title' value='" . htmlspecialchars($row['title']) . "'></td>
              <td><input type='text' name='description' value='" . htmlspecialchars($row['description']) . "'></td>
              <td>
                  <input type='file' name='lesson_file' accept='.pdf,.docx'><br>
                  Current: " . htmlspecialchars($row['lesson_file_name']) . "
              </td>
              <td>
                  <input type='file' name='thumbnail' accept='image/png, image/jpeg'><br>
                  Current: " . htmlspecialchars($row['thumbnail_name']) . "
              </td>
              <td>" . htmlspecialchars($row['create_time']) . "</td>
              <td>
                  <input type='hidden' name='lesson_id' value='" . $row['lesson_id'] . "'>
                  <input type='submit' name='update' value='Save'>
              </td>
              </form>";
    } else {
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['lesson_file_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['thumbnail_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['create_time']) . "</td>";
        echo "<td>
                <a href='Main_page.php?edit_id=" . $row['lesson_id'] . "'>Edit</a> |
                <a href='Main_page.php?id=" . $row['lesson_id'] . "' onclick=\"return confirm('Are you sure you want to delete this lesson?');\">Delete</a>
              </td>";
    }
    echo "</tr>";
}
echo "</table>";
?>

    <script src="teacher_main.js"></script>
=======
    <script src="../javascriptfile/teacher_main.js"></script>
>>>>>>> origin/main
</body>
</html>