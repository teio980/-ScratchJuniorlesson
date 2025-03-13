<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Submission</title>
</head>
<body>
    <h2>
        Submit Your ScratchJr Project 
        <?php
            if (isset($_GET['lesson_id'])) {
                $lesson_id = $_GET['lesson_id'];
                echo "$lesson_id";
            }
        ?>
    </h2>

    <form id="uploadForm" action="../phpfile/studentupload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
        
        <label for="file">Choose the file need to submit</label>
        <input type="file" id="file" name="file" required>
        <br><br>
        <button type="submit" name="savebtn">Upload</button>
    </form>

    <script src="../javascriptfile/studentsubmit.js"></script>
</body>
</html>
