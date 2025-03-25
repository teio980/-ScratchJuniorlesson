<?php
session_start();
include '../phpfile/connect.php';

if (isset($_GET['lesson_id'])) {
    $lesson_id = $_GET['lesson_id'];
    $query = "SELECT * FROM lessons WHERE lesson_id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $lesson = $result->fetch_assoc();
    } else {
        echo "<p>Lesson not found.</p>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Submission</title>
</head>
<body>

<h2>Submit Your ScratchJr Project for Lesson <?php echo $lesson['lesson_id']; ?></h2>
<p><strong>Title:</strong> <?php echo htmlspecialchars($lesson['title']); ?></p>
<p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($lesson['description'])); ?></p>
<p><strong>Expire Date:</strong> <span id="expireDate"><?php echo $lesson['expire_date']; ?></span></p>
<p><strong>Time Left:</strong> <span id="timeLeft"></span></p>

<form id="uploadForm" action="../phpfile/studentupload.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
    
    <label for="file">Choose the file to submit:</label>
    <input type="file" id="file" name="file" required>
    <br><br>
    <button type="submit" name="savebtn">Upload</button>
</form>

<script>
    function updateTimeLeft() {
        const expireDateStr = document.getElementById('expireDate').innerText;
        const expireDate = new Date(expireDateStr);
        const now = new Date();
        
        const timeDiff = expireDate - now;
        if (timeDiff <= 0) {
            document.getElementById('timeLeft').innerText = 'Submission lately';
            return;
        }
        
        const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
        
        document.getElementById('timeLeft').innerText = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    setInterval(updateTimeLeft, 1000);
    updateTimeLeft();
</script>

<script src="../javascriptfile/studentsubmit.js"></script>
</body>
</html>
