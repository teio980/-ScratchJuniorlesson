<?php
session_start();
include '../phpfile/connect.php';
include '../resheadAfterLogin.php';

if (isset($_POST['update'])) {
    $lesson_id = $_POST['lesson_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $lesson_file = $_FILES['lesson_file'];
    $thumbnail = $_FILES['thumbnail'];

    $sql = "SELECT lesson_file_name, thumbnail_name FROM lessons WHERE lesson_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lesson = $result->fetch_assoc();

    $lesson_file_name = $lesson['lesson_file_name'];
    if ($lesson_file['name']) {
        $lesson_file_name = $lesson_file['name'];
        move_uploaded_file($lesson_file['tmp_name'], "../phpfile/uploads/lesson/" . $lesson_file_name);
    }

    $thumbnail_name = $lesson['thumbnail_name'];
    if ($thumbnail['name']) {
        $thumbnail_name = $thumbnail['name'];
        move_uploaded_file($thumbnail['tmp_name'], "../phpfile/uploads/thumbnail/" . $thumbnail_name);
    }

    $sql = "UPDATE lessons SET title = ?, description = ?, lesson_file_name = ?, thumbnail_name = ? WHERE lesson_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sssss", $title, $description, $lesson_file_name, $thumbnail_name, $lesson_id);

    if ($stmt->execute()) {
        header("Location: lesson_management.php");
    } else {
        echo "Error updating lesson.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <title>Lesson Management</title>
</head>
<body>
    <div class="container">
        <h1>Lesson Management</h1>
        
        <div class="lesson-cards-container">
            <div class="add-lesson-card" onclick="location.href='upload_lesson.php'">
                <div class="add-lesson-icon">+</div>
                <div class="add-lesson-text">Add New Lesson</div>
            </div>
            
            <?php
            $sql = "SELECT lesson_id, title, description, lesson_file_name, thumbnail_name, create_time FROM lessons";
            $result = $connect->query($sql);
            
            while ($row = $result->fetch_assoc()):
                $thumbnailPath = !empty($row['thumbnail_name']) ? 
                    "../phpfile/uploads/thumbnail/" . $row['thumbnail_name'] : 
                    "../images/default-thumbnail.jpg";
            ?>
            <div class="lesson-card" onclick="toggleDetails('<?php echo $row['lesson_id']; ?>')">
                <img src="<?php echo $thumbnailPath; ?>" alt="Lesson Thumbnail" class="lesson-thumbnail">
                <div class="lesson-content">
                    <h3 class="lesson-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p class="lesson-description"><?php echo htmlspecialchars($row['description']); ?></p>
                    <div class="lesson-meta">
                        <span><?php echo htmlspecialchars($row['create_time']); ?></span>
                        <span><?php echo htmlspecialchars($row['lesson_file_name']); ?></span>
                    </div>
                </div>
                
                <div class="lesson-details" id="details-<?php echo $row['lesson_id']; ?>">
                    <div class="details-content">
                        <div class="detail-row">
                            <span class="detail-label">Title:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($row['title']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Description:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($row['description']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">File:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($row['lesson_file_name']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Created:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($row['create_time']); ?></span>
                        </div>
                    </div>
                    
                    <div class="lesson-actions">
                        <a href="lesson_management.php?edit_id=<?php echo $row['lesson_id']; ?>" class="edit-btn">Edit</a>
                        <a href="lesson_management.php?id=<?php echo $row['lesson_id']; ?>" 
                           class="delete-btn" 
                           onclick="return confirm('Are you sure you want to delete this lesson?');">Delete</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php if (isset($_GET['edit_id'])):
        $edit_id = $_GET['edit_id'];
        $edit_sql = "SELECT * FROM lessons WHERE lesson_id = ?";
        $edit_stmt = $connect->prepare($edit_sql);
        $edit_stmt->bind_param("s", $edit_id);
        $edit_stmt->execute();
        $edit_result = $edit_stmt->get_result();
        $edit_row = $edit_result->fetch_assoc();
    ?>
    <div id="editModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close" onclick="location.href='lesson_management.php'">&times;</span>
            <h2>Edit Lesson</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="lesson_id" value="<?php echo $edit_row['lesson_id']; ?>">
                
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($edit_row['title']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($edit_row['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="thumbnail">Thumbnail (Current: <?php echo htmlspecialchars($edit_row['thumbnail_name']); ?>):</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/png, image/jpeg">
                </div>

                <div class="form-group">
                    <label for="lesson_file">Lesson File (Current: <?php echo htmlspecialchars($edit_row['lesson_file_name']); ?>):</label>
                    <input type="file" id="lesson_file" name="lesson_file" accept=".pdf,.docx">
                </div>               
                
                <div class="form-actions">
                    <button type="submit" name="update" class="edit-btn">Save Changes</button>
                    <button type="button" onclick="location.href='lesson_management.php'" class="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function toggleDetails(lessonId) {
            const details = document.getElementById('details-' + lessonId);
            details.classList.toggle('expanded');
            
            document.querySelectorAll('.lesson-details').forEach(element => {
                if (element.id !== 'details-' + lessonId && element.classList.contains('expanded')) {
                    element.classList.remove('expanded');
                }
            });
        }
    </script>

    <button onclick="location.href='Main_page.php'">Back to Dashboard</button>

</body>
</html>
