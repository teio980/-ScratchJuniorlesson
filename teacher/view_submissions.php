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
    <title>Student Submissions</title>
    <script src="../javascriptfile/download_all.js"></script>
</head>
<body>
    <h2>Student Uploaded Lessons</h2>

    <button onclick="downloadAll()">Download All Lessons</button><br><br>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Lesson ID</th>
                <th>Filename</th>
                <th>Download</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $result = mysqli_query($connect, "SELECT * FROM projects");    
                $count = mysqli_num_rows($result);
                if ($count > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
            ?>      
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['lesson_id']; ?></td>
                <td><?php echo $row['filename']; ?></td>
                <td><a href="<?php echo $row['filepath']; ?>" class="download-link" data-filename="<?php echo $row['filename']; ?>" download>Download</a></td>
                <td>
                    <form action="../phpfile/submit_feedback.php" method="POST">
                        <input type="hidden" name="project_id" value="<?php echo $row['id']; ?>">
                        <textarea name="feedback" rows="2" cols="30" placeholder="Enter feedback"></textarea>
                        <button type="submit">Submit Feedback</button>
                    </form>
                </td>
            </tr>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No uploads found.</td></tr>";
                }
            ?>
        </tbody>
    </table>
    
    <button onclick="location.href='Main_page.php'">Back to Dashboard</button>
</body>
</html>