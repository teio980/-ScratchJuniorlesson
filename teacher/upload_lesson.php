<?php
session_start();
include '../phpfile/connect.php';
include '../resheadAfterLogin.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$typePrefix = '';

switch ($type) {
    case 'assignment':
        $typePrefix = 'Assignment - ';
        break;
    case 'material':
        $typePrefix = 'Material - ';
        break;
    case 'project':
        $typePrefix = 'Project - ';
        break;
    case 'exercise':
        $typePrefix = 'Exercise - ';
        break;
    default:
        $typePrefix = '';
        break;
}

$categoryOptions = [];
$sql = "SELECT category_id, category_name FROM categories";
$result = mysqli_query($connect, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $categoryOptions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <title>Upload Lesson</title>
</head>
<body>
    <div class="container">
        <h1>Upload New Lesson</h1>
        
        <form action="../phpfile/upload_lesson_process.php" method="POST" enctype="multipart/form-data" class="lesson-form">
            <div class="form-group">
                <label for="title">Lesson Title:</label>
                <input type="text" id="title" name="title" required>
                <input type="hidden" name="type_prefix" value="<?php echo htmlspecialchars($typePrefix); ?>">
            </div>

            <div class="form-group">
                <label for="category">Lesson Category:</label>
                <select id="category" name="category_id" required>
                    <option value="">-- Select a Category --</option>
                    <?php foreach ($categoryOptions as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="thumbnail">Lesson Thumbnail (PNG/JPG):</label>
                <input type="file" id="thumbnail" name="thumbnail_image" accept="image/png, image/jpeg">
            </div>
            
            <div class="form-group">
                <label for="expire_date">Expire Date:</label>
                <input type="date" id="expire_date" name="expire_date" required>
            </div>
            
            <div class="form-group">
                <label for="description">Lesson Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="lesson_file">Upload Lesson File (PDF/Word):</label>
                <input type="file" id="lesson_file" name="lesson_file" accept=".pdf, .docx">
            </div>
            
            <div class="form-actions">
                <button type="submit" name="savebtn" class="submit-btn">Submit Lesson</button>
                <button type="button" onclick="location.href='lesson_management.php'" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("expire_date").setAttribute("min", new Date().toISOString().split("T")[0]);
    </script>
</body>
</html>
