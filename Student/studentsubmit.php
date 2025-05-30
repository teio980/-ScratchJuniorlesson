<?php
session_start();
include '../phpfile/connect.php';

$user_id = $_SESSION['user_id'];
$availability_id = $_GET['availability_id'];

$query = "SELECT class_id, lesson_id, expire_date FROM class_work WHERE availability_id = '$availability_id'";
$result = mysqli_query($connect, $query);

$row = mysqli_fetch_assoc($result);
$class_id = $row['class_id'];
$lesson_id = $row['lesson_id'];
$expire_date = $row['expire_date'];

$query2 = "SELECT title, description, thumbnail_name, file_name
           FROM lessons WHERE lesson_id = '$lesson_id'";
$result2 = mysqli_query($connect, $query2);
$lesson = mysqli_fetch_assoc($result2);

$title = $lesson['title'];
$description = $lesson['description'];
$thumbnail_name = $lesson['thumbnail_name'];
$lesson_file_name = $lesson['file_name'];


$query3 = "SELECT * FROM student_submit WHERE student_id = '$user_id' AND class_id = '$class_id' AND lesson_id = '$lesson_id'";
$result3 = mysqli_query($connect, $query3);
$existing_submission = mysqli_fetch_assoc($result3);

$uploadMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = basename($_FILES['file']['name']);
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

    if (strtolower($file_ext) !== 'sjr') {
        $uploadMessage = "Only .sjr files are allowed.";
    } else {
        $target_dir = "uploads/";
        $filepath = $target_dir . $filename;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $sql_count = "SELECT COUNT(*) AS total FROM student_submit";
        $result_count = mysqli_query($connect, $sql_count);
        $row_count = mysqli_fetch_assoc($result_count);
        $submit_id = 'SS' . str_pad($row_count['total'] + 1, 6, '0', STR_PAD_LEFT);

        if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
            if ($existing_submission) {
                if (!empty($existing_submission['filepath']) && file_exists($existing_submission['filepath'])) {
                    unlink($existing_submission['filepath']);
                }

                $update = "UPDATE student_submit 
                           SET filename = '$filename', filepath = '$filepath', upload_time = NOW() 
                           WHERE submit_id = '{$existing_submission['submit_id']}'";
                mysqli_query($connect, $update);
                header("Location: Main_page.php?msg=updated");
                exit();
            } else {
                $insert = "INSERT INTO student_submit 
                           (submit_id, student_id, class_id, lesson_id, filename, filepath, upload_time)
                           VALUES ('$submit_id', '$user_id', '$class_id', '$lesson_id', '$filename', '$filepath', NOW())";
                mysqli_query($connect, $insert);
                header("Location: Main_page.php?msg=submitted");
                exit();
            }
        } else {
            $uploadMessage = "Failed to upload file.";
        }
    }
}

$current_time = new DateTime();
$expire_time = new DateTime($expire_date);
$interval = $current_time->diff($expire_time);
if ($expire_time > $current_time) {
    $time_left = $interval->d . " days " . $interval->h . " hours " . $interval->i . " minutes";
} else {
    $time_left = "The deadline has passed.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submit Assignment</title>
  <link rel="stylesheet" href="../cssfile/student_submit.css"> 
</head>
<body>

  <div class="submission-wrapper">
    <h2><?php echo htmlspecialchars($title); ?></h2>
    <p class="description"><?php echo nl2br(htmlspecialchars($description)); ?></p>

    <div class="info">
      <strong>Submission Deadline:</strong>
      <?php echo date("F j, Y, g:i A", strtotime($expire_date)); ?>
      <br>
      <strong>Time Remaining:</strong>
      <?php echo $time_left; ?>
    </div>

    <div class="thumbnail">
      <img src="<?php echo '../phpfile/uploads/thumbnail/' . htmlspecialchars($thumbnail_name); ?>" alt="Lesson Thumbnail">
    </div>

    <a href="../phpfile/uploads_teacher/<?php echo urlencode($lesson_file_name); ?>" class="download-link" download>
        Download Lesson File
    </a>


    <?php if ($existing_submission): ?>
      <div class="current-submission">
        <strong>Current Submission:</strong>
        <p>Filename: <?php echo htmlspecialchars($existing_submission['filename']); ?></p>
        <a href="<?php echo htmlspecialchars($existing_submission['filepath']); ?>" target="_blank">View Submitted File</a>
        <p>You can re-upload to update your submission below.</p>
      </div>
    <?php endif; ?>

    <form action="studentsubmit.php?availability_id=<?php echo urlencode($availability_id); ?>" method="POST" enctype="multipart/form-data">
      <div class="file-upload">
        <label for="file">
          📁 Choose .sjr file to upload
        </label>
        <input type="file" name="file" id="file" accept=".sjr" required>
      </div>
      <button type="submit">Submit</button>
    </form>

    <?php if (!empty($uploadMessage)) : ?>
      <p class="error-message"><?php echo $uploadMessage; ?></p>
    <?php endif; ?>
  </div>

  <a href="Main_page.php" class="floating-return-button">← Return</a>

</body>
</html>
