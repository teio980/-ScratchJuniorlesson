<?php include '../phpfile/connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Submit ScratchJr Lesson</title>
</head>
<body>

    <h2>Submit a Lesson for ScratchJr</h2>
    <form action="../phpfile/teacherupload.php" method="POST">
        <label for="title">Lesson Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Lesson Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>

        <button type="submit" name="savebtn">Submit Lesson</button>
    </form>

    <h2>Student Uploaded Lessons</h2>

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
                <td><a href="<?php echo $row['filepath']; ?>" download>Download</a></td>
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
