<?php include '../phpfile/connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Submit ScratchJr Lesson</title>
    <script>
        function downloadAll() {
            // Get all download links in the table
            var links = document.querySelectorAll('.download-link');
            links.forEach(function(link) {
                var a = document.createElement('a');
                a.href = link.href;
                a.download = link.getAttribute('data-filename'); // Set a filename if desired
                a.click(); // Trigger the download
            });
        }
    </script>
</head>
<body>

    <h2>Submit a Lesson for ScratchJr</h2>
    <form action="../phpfile/teacherupload.php" method="POST">
        <label for="title">Lesson Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Lesson Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>

        <label for="expire_date">Expire Date:</label>
        <input type="datetime-local" id="expire_date" name="expire_date" required><br><br>
    
        <button type="submit" name="savebtn">Submit Lesson</button>
    </form>

    <h2>Student Uploaded Lessons</h2>

    <button onclick="downloadAll()">Download All Lessons</button><br><br>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Lesson ID</th>
                <th>Filename</th>
                <th>Download</th>
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
            </tr>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='4'>No uploads found.</td></tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>
