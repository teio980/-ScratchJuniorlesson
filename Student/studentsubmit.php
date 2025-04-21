<?php
session_start();

include '../phpfile/connect.php';  // This file should set up $connect (MySQLi)

$user_id = $_SESSION['user_id'];
$availability_id = $_GET['availability_id'] ?? null;

if (!$availability_id) {
    echo "Invalid availability ID.";
    exit();
}

// Step 1: Get class_id, lesson_id, expire_date
$query = "SELECT class_id, lesson_id, expire_date FROM class_work WHERE availability_id = '$availability_id'";
$result = mysqli_query($connect, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Class work not found.";
    exit();
}

$row = mysqli_fetch_assoc($result);
$class_id = $row['class_id'];
$lesson_id = $row['lesson_id'];
$expire_date = $row['expire_date'];

// Step 2: Get lesson details
$query2 = "SELECT title, description, thumbnail_name, thumbnail_path, lesson_file_name, file_path 
           FROM lessons WHERE lesson_id = '$lesson_id'";
$result2 = mysqli_query($connect, $query2);

if (!$result2 || mysqli_num_rows($result2) == 0) {
    echo "Lesson not found.";
    exit();
}

$lesson = mysqli_fetch_assoc($result2);
$title = $lesson['title'];
$description = $lesson['description'];
$thumbnail_name = $lesson['thumbnail_name'];
$thumbnail_path = $lesson['thumbnail_path'];
$lesson_file_name = $lesson['lesson_file_name'];
$lesson_file_path = $lesson['file_path'];

$uploadMessage = "";

// Step 3: Check if the student has already submitted
$query3 = "SELECT * FROM student_submit WHERE student_id = '$user_id' AND class_id = '$class_id' AND lesson_id = '$lesson_id'";
$result3 = mysqli_query($connect, $query3);
$existing_submission = mysqli_fetch_assoc($result3);

// Step 4: Handle file upload (for new submission or editing)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = basename($_FILES['file']['name']);
    $target_dir = "uploads/";
    $filepath = $target_dir . $filename;

    // Create upload folder if not exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Auto-generate submit_id like TCH000001
    $sql_count = "SELECT COUNT(*) AS total FROM student_submit";
    $result_count = mysqli_query($connect, $sql_count);
    $row_count = mysqli_fetch_assoc($result_count);
    $submit_Qty = $row_count['total'];
    $submit_id = 'SS' . str_pad($submit_Qty + 1, 6, '0', STR_PAD_LEFT);

    // Upload file and insert or update
    if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
        if ($existing_submission) {
            // If the student already submitted, update the record
            $update = "UPDATE student_submit 
                       SET filename = '$filename', filepath = '$filepath', upload_time = NOW() 
                       WHERE submit_id = '{$existing_submission['submit_id']}'";
            $update_result = mysqli_query($connect, $update);

            if ($update_result) {
                $uploadMessage = "File updated successfully.";
            } else {
                $uploadMessage = "Error updating submission.";
            }
        } else {
            // If no previous submission, insert a new record
            $insert = "INSERT INTO student_submit (submit_id, student_id, class_id, lesson_id, filename, filepath, upload_time)
                       VALUES ('$submit_id', '$user_id', '$class_id', '$lesson_id', '$filename', '$filepath', NOW())";
            $insert_result = mysqli_query($connect, $insert);

            if ($insert_result) {
                $uploadMessage = "File uploaded and saved successfully.";
            } else {
                $uploadMessage = "Error saving submission to database.";
            }
        }
    } else {
        $uploadMessage = "Failed to upload file.";
    }
}

// Calculate time left
$current_time = new DateTime();
$expire_time = new DateTime($expire_date);
$interval = $current_time->diff($expire_time);

// Time left message
$time_left = "";
if ($expire_time > $current_time) {
    $time_left = "Time remaining: ";
    if ($interval->d > 0) {
        $time_left .= $interval->d . " days ";
    }
    if ($interval->h > 0) {
        $time_left .= $interval->h . " hours ";
    }
    if ($interval->i > 0) {
        $time_left .= $interval->i . " minutes ";
    }
} else {
    $time_left = "The deadline has passed.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Assignment</title>
</head>
<body>

<h2><?php echo $title; ?></h2>
<p><?php echo nl2br($description); ?></p>

<?php if (!empty($expire_date)) : ?>
    <strong>Submission Deadline:</strong> <?php echo date("F j, Y, g:i A", strtotime($expire_date)); ?><br><br>
    <strong><?php echo $time_left; ?></strong><br><br>
<?php endif; ?>

<?php if (!empty($thumbnail_path)) : ?>
    <img src="<?php echo '../phpfile/uploads/thumbnail/' . $thumbnail_name; ?>" alt="<?php echo $thumbnail_name; ?>" width="300"><br><br>
<?php endif; ?>

<?php if (!empty($lesson_file_path)) : ?>
    <a href="<?php echo $lesson_file_path; ?>" download><?php echo $lesson_file_name; ?></a><br><br>
<?php endif; ?>

<?php if ($existing_submission) : ?>
    <h3>Your Current Submission:</h3>
    <p>Filename: <?php echo $existing_submission['filename']; ?></p>
    <p><a href="<?php echo $existing_submission['filepath']; ?>" target="_blank">View Submitted File</a></p>
    <p>Click below to update your submission:</p>
<?php else : ?>
    <p>You have not submitted this assignment yet.</p>
<?php endif; ?>

<form action="studentsubmit.php?availability_id=<?php echo urlencode($availability_id); ?>" method="POST" enctype="multipart/form-data">
    <label>Select file to upload:</label><br>
    <input type="file" name="file" required><br><br>
    <button type="submit">Submit</button>
</form>

<?php if (!empty($uploadMessage)) : ?>
    <p><?php echo $uploadMessage; ?></p>
<?php endif; ?>

</body>
</html>
