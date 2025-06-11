<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';
include 'resheadteacher.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$typePrefix = '';

switch ($type) {
    case 'assignment':
        $typePrefix = 'Assignment - ';
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

$presetCriteria = [
    'Completion',
    'Creativity',
    'Presentation',
    'Originality',
    'Technical',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/Teachermain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/upload_lesson.css">
    <title>Upload Task</title>
    <script id="presetCriteria" type="application/json"><?php echo json_encode($presetCriteria); ?></script>
</head>
<body>
    <div class="dashboard-container">
        <div class="container">
            <h1>Upload New Task</h1>
            
            <form action="../phpfile/upload_lesson_process.php" method="POST" enctype="multipart/form-data" class="lesson-form">
                <div class="form-left">
                    <div class="form-group">
                        <label for="category">Category <span style="color:red">*</span></label>
                        <select id="category" name="category" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="Assignment">Assignment</option>
                            <option value="Project">Project</option>
                            <option value="Exercise">Exercise</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Title <span style="color:red">*</span></label>
                        <input type="text" id="title" name="title" required>
                        <input type="hidden" name="type_prefix" value="<?php echo htmlspecialchars($typePrefix); ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Task Description:</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail_image">Thumbnail Image (JPG/PNG):</label>
                        <input type="file" name="thumbnail_image" id="thumbnail_image" accept=".jpg,.jpeg,.png">
                        <span class="file-name">No file chosen</span>
                    </div>

                    <div class="form-group">
                        <label for="lesson_file">Task File (PDF/Word):</label>
                        <input type="file" name="lesson_file" id="lesson_file" accept=".pdf,.doc,.docx">
                        <span class="file-name">No file chosen</span>
                    </div>
                </div>

                <div class="form-right">
                    <div class="form-group">
                        <label>Grading Criteria <span class="required">*</span></label>
                        <div class="form-group">
                            <label for="criteria_count">Number of Criteria</label>
                            <select id="criteria_count" name="criteria_count">
                                <?php for($i=1; $i<=10; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div id="criteriaFieldsContainer"></div>
                        <input type="hidden" id="scoring_criteria" name="scoring_criteria">
                        <div id="criteriaPreview"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="savebtn" class="submit-btn">Upload Task</button>
                    <button type="button" onclick="location.href='lesson_management.php'" class="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</body>
    <script src="../javascriptfile/upload_lesson.js"></script>
</html>