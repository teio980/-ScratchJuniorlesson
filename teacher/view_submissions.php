<?php
session_start();
include '../phpfile/connect.php';


if (isset($_POST['submit_feedback'])) {
    $submit_id = $_POST['submit_id'];
    $student_id = $_POST['student_id']; // Get student_id from hidden input
    $rating = $_POST['rating']; 
    $comments = $_POST['comments']; 

    if ($rating < 1 || $rating > 10 || empty($comments)) {
        echo "Please provide a valid rating (1-10) and comment.";
    } else {
        $query = "INSERT INTO student_submit_feedback (submit_id, student_id, rating, comments) 
                  VALUES ('$submit_id', '$student_id', '$rating', '$comments')";

        if (mysqli_query($connect, $query)) {
            echo "Feedback submitted successfully!";
        } else {
            echo "Error: " . mysqli_error($connect);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <link rel="stylesheet" href="../cssfile/view_ssub.css">
    <title>Student Submissions</title>
    <script src="../javascriptfile/download_all.js"></script>
    <script>
        function openFeedbackModal(submit_id, student_id) {
            document.getElementById('feedbackModal').style.display = 'block';
            document.getElementById('submit_id').value = submit_id;
            document.getElementById('student_id').value = student_id;
        }

        function closeFeedbackModal() {
            document.getElementById('feedbackModal').style.display = 'none';
        }
    </script>
</head>
<body>
    <h2>Student Uploaded Lessons</h2>

    <button onclick="downloadAll()">Download All Lessons</button><br><br>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Student ID</th>
                <th>Lesson ID</th>
                <th>Filename</th>
                <th>Download</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $result = mysqli_query($connect, "SELECT ss.*, s.S_username, s.student_id FROM student_submit ss JOIN student s ON ss.student_id = s.student_id");                
            $count = mysqli_num_rows($result);
            if ($count > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
        ?>      
            <tr>
                <td><?php echo $row['submit_id']; ?></td>
                <td><?php echo $row['S_username']; ?></td>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo $row['lesson_id']; ?></td>
                <td><?php echo $row['filename']; ?></td>
                <td><a href="../Student/<?php echo $row['filepath']; ?>" class="download-link" data-filename="<?php echo $row['filename']; ?>" download>Download</a></td>
                <td>
                    <button onclick="openFeedbackModal('<?php echo $row['submit_id']; ?>', '<?php echo $row['student_id']; ?>')">Give Feedback</button>
                </td>
            </tr>
        <?php
                }
            } else {
                echo "<tr><td colspan='7'>No uploads found.</td></tr>";
            }
        ?>
        </tbody>
    </table>

    <button onclick="location.href='Main_page.php'">Back to Dashboard</button>

    <!-- Feedback Modal -->
    <div id="feedbackModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0, 0, 0, 0.5);">
        <div style="background-color:white; margin:10% auto; padding:20px; width:300px; border-radius:10px;">
            <h3>Provide Feedback</h3>
            <form action="view_submissions.php" method="POST">
                <input type="hidden" name="submit_id" id="submit_id" value="">
                <input type="hidden" name="student_id" id="student_id" value="">
                
                <label for="rating">Rating (1-10):</label><br>
                <input type="number" name="rating" min="1" max="10" required><br><br>

                <label for="comments">Comments:</label><br>
                <textarea name="comments" required></textarea><br><br>

                <button type="submit" name="submit_feedback">Submit Feedback</button>
                <button type="button" onclick="closeFeedbackModal()">Close</button>
            </form>
        </div>
    </div>
</body>
</html>
