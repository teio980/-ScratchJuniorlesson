<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';
include 'resheadteacher.php';

if (isset($_GET['id'])) {
    $lesson_id = $_GET['id'];
    
    $check_sql = "SELECT COUNT(*) FROM student_submit 
                 WHERE lesson_id = ? AND score != -1";
    $check_stmt = $connect->prepare($check_sql);
    $check_stmt->bind_param("s", $lesson_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $row = $check_result->fetch_row();
    
    if ($row[0] > 0) {
        $_SESSION['error'] = "Cannot delete lesson that has student submissions with scores!";
        header("Location: lesson_management.php");
        exit();
    }
    
    $sql = "SELECT file_name, thumbnail_name FROM lessons WHERE lesson_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lesson = $result->fetch_assoc();
    
    $connect->begin_transaction();
    
    try {
        $delete_class_work_sql = "DELETE FROM class_work WHERE lesson_id = ?";
        $delete_class_work_stmt = $connect->prepare($delete_class_work_sql);
        $delete_class_work_stmt->bind_param("s", $lesson_id);
        $delete_class_work_stmt->execute();
        
        $delete_submit_sql = "DELETE FROM student_submit WHERE lesson_id = ?";
        $delete_submit_stmt = $connect->prepare($delete_submit_sql);
        $delete_submit_stmt->bind_param("s", $lesson_id);
        $delete_submit_stmt->execute();
        
        $delete_sql = "DELETE FROM lessons WHERE lesson_id = ?";
        $delete_stmt = $connect->prepare($delete_sql);
        $delete_stmt->bind_param("s", $lesson_id);
        
        if ($delete_stmt->execute()) {
            $lesson_file_path = "../phpfile/uploads/lesson/" . $lesson['file_name'];
            $thumbnail_path = "../phpfile/uploads/thumbnail/" . $lesson['thumbnail_name'];
            
            if (file_exists($lesson_file_path)) unlink($lesson_file_path);
            if (file_exists($thumbnail_path)) unlink($thumbnail_path);
            
            $connect->commit();
            $_SESSION['success'] = "Lesson deleted successfully!";
        } else {
            $connect->rollback();
            $_SESSION['error'] = "Failed to delete lesson!";
        }
    } catch (Exception $e) {
        $connect->rollback();
        $_SESSION['error'] = "Error during deletion: " . $e->getMessage();
    }
    
    header("Location: lesson_management.php");
    exit();
}

if (isset($_GET['delete_material'])) {
    $material_id = $_GET['delete_material'];
    
    $sql = "SELECT file_name FROM teacher_materials WHERE material_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $material = $result->fetch_assoc();
    
    $delete_sql = "DELETE FROM teacher_materials WHERE material_id = ?";
    $delete_stmt = $connect->prepare($delete_sql);
    $delete_stmt->bind_param("s", $material_id);
    
    if ($delete_stmt->execute()) {
        $file_path = "../phpfile/upload_teacher_material/" . $material['file_name'];
        if (file_exists($file_path)) unlink($file_path);
        
        $_SESSION['success'] = "Material deleted successfully!";
    }
    header("Location: lesson_management.php?tab=materials");
    exit();
}

if (isset($_POST['update'])) {
    $lesson_id = $_POST['lesson_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $grading_criteria = isset($_POST['scoring_criteria']) ? $_POST['scoring_criteria'] : '';
    
    $sql = "SELECT file_name, thumbnail_name FROM lessons WHERE lesson_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lesson = $result->fetch_assoc();
    
    $old_file_name = $lesson['file_name'];
    $old_thumbnail_name = $lesson['thumbnail_name'];
    $new_file_name = $old_file_name;
    $new_thumbnail_name = $old_thumbnail_name;
    
    if (!empty($_FILES['lesson_file']['name'])) {
        $old_file_path = "../phpfile/uploads/lesson/" . $old_file_name;
        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }
        
        $original_filename = basename($_FILES['lesson_file']['name']);
        $new_file_name = generateUniqueFilename("../phpfile/uploads/lesson/", $original_filename);
        move_uploaded_file($_FILES['lesson_file']['tmp_name'], "../phpfile/uploads/lesson/" . $new_file_name);
    }
    
    if (!empty($_FILES['thumbnail']['name'])) {
        $old_thumb_path = "../phpfile/uploads/thumbnail/" . $old_thumbnail_name;
        if (file_exists($old_thumb_path)) {
            unlink($old_thumb_path);
        }
        
        $original_thumbnail = basename($_FILES['thumbnail']['name']);
        $new_thumbnail_name = generateUniqueFilename("../phpfile/uploads/thumbnail/", $original_thumbnail);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], "../phpfile/uploads/thumbnail/" . $new_thumbnail_name);
    }
    
    $update_sql = "UPDATE lessons SET 
                  title = ?, 
                  description = ?, 
                  category = ?,
                  grading_criteria = ?,
                  file_name = ?, 
                  thumbnail_name = ? 
                  WHERE lesson_id = ?";
                  
    $stmt = $connect->prepare($update_sql);
    $stmt->bind_param("sssssss", $title, $description, $category, $grading_criteria, $new_file_name, $new_thumbnail_name, $lesson_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Lesson updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update lesson!";
    }
    header("Location: lesson_management.php");
    exit();
}


if (isset($_POST['update_material'])) {
    $material_id = $_POST['material_id'];
    $title = $_POST['material_title'];
    $description = $_POST['material_description'];
    $class_id = $_POST['class_id'];
    
    $sql = "SELECT file_name FROM teacher_materials WHERE material_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $material = $result->fetch_assoc();
    
    $old_file_name = $material['file_name'];
    $new_file_name = $old_file_name;
    
    if (!empty($_FILES['material_file']['name'])) {
        $old_file_path = "../phpfile/upload_teacher_material/" . $old_file_name;
        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }
        
        $original_filename = basename($_FILES['material_file']['name']);
        $new_file_name = str_replace(' ', '_', $original_filename);
        move_uploaded_file($_FILES['material_file']['tmp_name'], "../phpfile/upload_teacher_material/" . $new_file_name);
    }
    
    $update_sql = "UPDATE teacher_materials SET 
                  title = ?, 
                  description = ?, 
                  class_id = ?,
                  file_name = ? 
                  WHERE material_id = ?";
                  
    $stmt = $connect->prepare($update_sql);
    $stmt->bind_param("sssss", $title, $description, $class_id, $new_file_name, $material_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Material updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update material!";
    }
    header("Location: lesson_management.php?tab=materials");
    exit();
}

function generateUniqueFilename($directory, $filename) {
    $counter = 1;
    $fileinfo = pathinfo($filename);
    $name = $fileinfo['filename'];
    $extension = isset($fileinfo['extension']) ? '.' . $fileinfo['extension'] : '';
    
    while (file_exists($directory . $filename)) {
        $filename = $name . '(' . $counter . ')' . $extension;
        $counter++;
    }
    
    return $filename;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management</title>
    <link rel="stylesheet" href="../cssfile/Teachermain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/lesson_management.css">
</head>
<body>
    <div class="container">
        <h1>Teaching Content Management</h1>
        
        <div class="tab-container">
            <div class="tab active" onclick="switchTab('lessons')">Tasks</div>
            <div class="tab" onclick="switchTab('materials')">Materials</div>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-box success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <span class="close-alert">&times;</span>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-box error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <span class="close-alert">&times;</span>
            </div>
        <?php endif; ?>
        
        <div id="lessons" class="content-section active">
            <div class="lesson-cards-container">
                <div class="add-lesson-card" onclick="location.href='upload_lesson.php'">
                    <div class="add-lesson-icon">+</div>
                    <div class="add-lesson-text">Add New Task</div>
                </div>
                
                <?php
                $sql = "SELECT * FROM lessons ORDER BY create_time DESC";
                $result = $connect->query($sql);
                
                while ($row = $result->fetch_assoc()):
                    $thumbnailPath = !empty($row['thumbnail_name']) ? 
                        "../phpfile/uploads/thumbnail/" . $row['thumbnail_name'] : 
                        "../images/default-thumbnail.jpg";
                ?>
                <div class="lesson-card">
                    <img src="<?php echo $thumbnailPath; ?>" alt="Lesson Thumbnail" class="lesson-thumbnail">
                    <div class="lesson-content">
                        <h3 class="lesson-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="lesson-description"><?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="lesson-meta">
                            <span>Created: <?php echo date('Y-m-d', strtotime($row['create_time'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="lesson-actions">
                        <a href="lesson_management.php?edit_id=<?php echo $row['lesson_id']; ?>" class="edit-btn">Edit</a>
                        <a href="lesson_management.php?id=<?php echo $row['lesson_id']; ?>" 
                           class="delete-btn" 
                           onclick="return confirm('Are you sure you want to delete this lesson?')">Delete</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        
        <div id="materials" class="content-section <?php echo (isset($_GET['tab']) && $_GET['tab']) == 'materials' ? 'active' : ''; ?>">
            <div class="lesson-cards-container">
                <div class="add-lesson-card" onclick="location.href='upload_teacher_material.php'">
                    <div class="add-lesson-icon">+</div>
                    <div class="add-lesson-text">Add New Material</div>
                </div>
                
                <?php
                $teacher_id = $_SESSION['user_id'];
                $sql = "SELECT tm.*, c.class_name 
                        FROM teacher_materials tm
                        JOIN class c ON tm.class_id = c.class_id
                        WHERE tm.teacher_id = ? 
                        ORDER BY tm.create_time DESC";
                $stmt = $connect->prepare($sql);
                $stmt->bind_param("s", $teacher_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()):
                ?>
                <div class="lesson-card">
                    <div class="lesson-content">
                        <h3 class="lesson-title"><?= htmlspecialchars($row['title']) ?></h3>
                        <p class="lesson-description"><?= htmlspecialchars($row['description']) ?></p>
                        <div class="lesson-meta">
                            <span>Class: <?= htmlspecialchars($row['class_name']) ?></span>
                            <span>Uploaded: <?= date('Y-m-d', strtotime($row['create_time'])) ?></span>
                            <span>File: <?= htmlspecialchars($row['file_name']) ?></span>
                        </div>
                    </div>
                    
                    <div class="lesson-actions">
                        <a href="lesson_management.php?edit_material_id=<?= $row['material_id'] ?>&tab=materials" 
                           class="edit-btn">Edit</a>
                        <a href="lesson_management.php?delete_material=<?= $row['material_id'] ?>" 
                           class="delete-btn" 
                           onclick="return confirm('Delete this material?')">Delete</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <?php 
            if (isset($_GET['edit_id'])):
                $edit_id = $_GET['edit_id'];
                $edit_sql = "SELECT * FROM lessons WHERE lesson_id = ?";
                $edit_stmt = $connect->prepare($edit_sql);
                $edit_stmt->bind_param("s", $edit_id);
                $edit_stmt->execute();
                $edit_result = $edit_stmt->get_result();
                $edit_row = $edit_result->fetch_assoc();
            
            $existing_criteria = [];
            if (!empty($edit_row['grading_criteria'])) {
                $criteria_pairs = explode('|', $edit_row['grading_criteria']);
                foreach ($criteria_pairs as $pair) {
                    list($name, $points) = explode(':', $pair);
                    $existing_criteria[] = [
                        'name' => $name,
                        'points' => $points
                    ];
                }
            }
        ?>
        <div id="editModal" class="modal" style="display: block;">
            <div class="modal-content">
                <span class="close" onclick="location.href='lesson_management.php'">&times;</span>
                <h2>Edit Task</h2>
                <div class="modal-scroll-container">
                    <form method="post" enctype="multipart/form-data" id="lessonEditForm">
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
                            <label for="thumbnail">Thumbnail (JPG/PNG only):</label>
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png">
                            <div class="file-info">Current: <?php echo htmlspecialchars($edit_row['thumbnail_name']); ?></div>
                            <div id="thumbnailError" class="error-message" style="color: red; display: none;"></div>
                        </div>

                        <div class="form-group">
                            <label for="lesson_file">Task File (PDF/DOC/DOCX only):</label>
                            <input type="file" id="lesson_file" name="lesson_file" accept=".pdf,.doc,.docx">
                            <div class="file-info">Current: <?php echo htmlspecialchars($edit_row['file_name']); ?></div>
                            <div id="lessonFileError" class="error-message" style="color: red; display: none;"></div>
                        </div>
                        
                        <div class="form-group compact-criteria">
                            <label>Grading Criteria</label>
                            <div class="criteria-controls">
                                <div>
                                    <label for="criteria_count">Number of Criteria:</label>
                                    <select id="criteria_count" name="criteria_count" class="small-select">
                                        <?php for($i=1; $i<=10; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php echo count($existing_criteria) == $i ? 'selected' : ''; ?>>
                                                <?php echo $i; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <button type="button" id="updateCriteriaBtn" class="small-btn">Update</button>
                            </div>

                            <div id="criteriaFieldsContainer" class="compact-container"></div>
                            <input type="hidden" id="scoring_criteria" name="scoring_criteria" value="<?php echo htmlspecialchars($edit_row['grading_criteria']); ?>">
                            <div id="criteriaPreview" class="compact-preview"></div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="update" class="submit-btn">Save</button>
                            <button type="button" onclick="location.href='lesson_management.php'" class="cancel-btn">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        document.getElementById('lessonEditForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            const thumbnailInput = document.getElementById('thumbnail');
            if (thumbnailInput.files.length > 0) {
                const allowedThumbnailTypes = ['image/jpeg', 'image/png'];
                const file = thumbnailInput.files[0];
                
                if (!allowedThumbnailTypes.includes(file.type)) {
                    document.getElementById('thumbnailError').textContent = 'Only JPG/JPEG/PNG images are allowed';
                    document.getElementById('thumbnailError').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('thumbnailError').style.display = 'none';
                }
            }
            
            const lessonFileInput = document.getElementById('lesson_file');
            if (lessonFileInput.files.length > 0) {
                const allowedLessonTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                const file = lessonFileInput.files[0];
                const fileExt = file.name.split('.').pop().toLowerCase();
                
                if (!allowedLessonTypes.includes(file.type) && !['pdf','doc','docx'].includes(fileExt)) {
                    document.getElementById('lessonFileError').textContent = 'Only PDF/DOC/DOCX files are allowed';
                    document.getElementById('lessonFileError').style.display = 'block';
                    isValid = false;
                } else {
                    document.getElementById('lessonFileError').style.display = 'none';
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                return false;
            }
            
            return confirm('Are you sure you want to save these changes?');
        });

        const presetCriteria = ['Completion', 'Creativity', 'Presentation', 'Originality', 'Technical'];

        function updateCriteriaFields() {
            const count = parseInt(document.getElementById('criteria_count').value);
            const container = document.getElementById('criteriaFieldsContainer');
            container.innerHTML = '';
            
            const existingCriteria = <?php echo json_encode($existing_criteria); ?>;
            
            for (let i = 0; i < count; i++) {
                const existing = existingCriteria[i] || {};
                const isCustom = existing.name && !presetCriteria.includes(existing.name);
                
                const row = document.createElement('div');
                row.className = 'compact-criteria-row';
                
                const nameSelect = document.createElement('select');
                nameSelect.className = 'criteria-name';
                nameSelect.name = `criteria_name_${i}`;
                nameSelect.required = true;
                
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select criteria';
                nameSelect.appendChild(defaultOption);
                
                presetCriteria.forEach(criterion => {
                    const option = document.createElement('option');
                    option.value = criterion;
                    option.textContent = criterion;
                    option.selected = (existing.name === criterion);
                    nameSelect.appendChild(option);
                });
                
                const customOption = document.createElement('option');
                customOption.value = 'custom';
                customOption.textContent = 'Custom...';
                customOption.selected = isCustom;
                nameSelect.appendChild(customOption);
                
                const customInput = document.createElement('input');
                customInput.type = 'text';
                customInput.className = 'criteria-custom-name';
                customInput.placeholder = 'Enter custom name';
                customInput.style.display = isCustom ? 'inline-block' : 'none';
                customInput.value = isCustom ? existing.name : '';
                
                const pointsInput = document.createElement('input');
                pointsInput.type = 'number';
                pointsInput.className = 'criteria-points';
                pointsInput.name = `criteria_points_${i}`;
                pointsInput.min = '1';
                pointsInput.max = '100';
                pointsInput.value = existing.points || '10';
                pointsInput.required = true;
                
                row.appendChild(nameSelect);
                row.appendChild(customInput);
                row.appendChild(document.createTextNode(' Points: '));
                row.appendChild(pointsInput);
                
                nameSelect.addEventListener('change', function() {
                    customInput.style.display = this.value === 'custom' ? 'inline-block' : 'none';
                    if (this.value !== 'custom') {
                        customInput.value = this.value;
                    }
                    updateCriteriaPreview();
                });
                
                customInput.addEventListener('input', updateCriteriaPreview);
                pointsInput.addEventListener('input', updateCriteriaPreview);
                
                container.appendChild(row);
            }
            
            updateCriteriaPreview();
        }

        function updateCriteriaPreview() {
            const count = parseInt(document.getElementById('criteria_count').value);
            let criteria = [];
            let previewHTML = "<strong>Current Criteria:</strong><ul class='compact-list'>";
            
            for (let i = 0; i < count; i++) {
                const nameSelect = document.querySelector(`select[name="criteria_name_${i}"]`);
                const customInput = document.querySelector(`.criteria-custom-name`);
                const pointsInput = document.querySelector(`input[name="criteria_points_${i}"]`);
                
                if (!nameSelect || !pointsInput) continue;
                
                const name = nameSelect.value === 'custom' 
                    ? (customInput ? customInput.value : '') 
                    : nameSelect.value;
                const points = pointsInput.value;
                
                if (name && points) {
                    criteria.push(`${name}:${points}`);
                    previewHTML += `<li>${name}: ${points} points</li>`;
                }
            }
            
            previewHTML += "</ul>";
            document.getElementById('scoring_criteria').value = criteria.join('|');
            document.getElementById('criteriaPreview').innerHTML = criteria.length > 0 
                ? previewHTML 
                : "<p>No criteria set</p>";
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('updateCriteriaBtn').addEventListener('click', updateCriteriaFields);
            
            updateCriteriaFields();
            
            document.getElementById('thumbnail').addEventListener('change', function() {
                const allowedTypes = ['image/jpeg', 'image/png'];
                if (this.files.length > 0 && !allowedTypes.includes(this.files[0].type)) {
                    document.getElementById('thumbnailError').textContent = 'Only JPG/JPEG/PNG images are allowed';
                    document.getElementById('thumbnailError').style.display = 'block';
                } else {
                    document.getElementById('thumbnailError').style.display = 'none';
                }
            });
            
            document.getElementById('lesson_file').addEventListener('change', function() {
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                const file = this.files[0];
                if (file) {
                    const fileExt = file.name.split('.').pop().toLowerCase();
                    if (!allowedTypes.includes(file.type) && !['pdf','doc','docx'].includes(fileExt)) {
                        document.getElementById('lessonFileError').textContent = 'Only PDF/DOC/DOCX files are allowed';
                        document.getElementById('lessonFileError').style.display = 'block';
                    } else {
                        document.getElementById('lessonFileError').style.display = 'none';
                    }
                }
            });
        });
        </script>
        <?php endif; ?>

        <?php if (isset($_GET['edit_material_id'])): 
            $edit_material_id = $_GET['edit_material_id'];
            $edit_material_sql = "SELECT * FROM teacher_materials WHERE material_id = ?";
            $edit_material_stmt = $connect->prepare($edit_material_sql);
            $edit_material_stmt->bind_param("s", $edit_material_id);
            $edit_material_stmt->execute();
            $edit_material_result = $edit_material_stmt->get_result();
            $edit_row = $edit_material_result->fetch_assoc();
        ?>

        <style>
        .error-message {
            color: red;
            margin-top: 5px;
        }
        </style>
        
        <div id="editMaterialModal" class="modal" style="display: block;">
            <div class="modal-content">
                <span class="close" onclick="location.href='lesson_management.php?tab=materials'">&times;</span>
                <h2>Edit Material</h2>
                <form method="post" enctype="multipart/form-data" id="materialEditForm">
                    <input type="hidden" name="material_id" value="<?php echo $edit_row['material_id']; ?>">
                    
                    <div class="form-group">
                        <label for="material_title" class="required-field">Title:</label>
                        <input type="text" id="material_title" name="material_title" value="<?php echo htmlspecialchars($edit_row['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="material_description" class="required-field">Description:</label>
                        <textarea id="material_description" name="material_description" required><?php echo htmlspecialchars($edit_row['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="class_id" class="required-field">Class:</label>
                        <select name="class_id" id="class_id" required>
                            <?php
                            $class_sql = "SELECT c.class_id, c.class_name FROM class c
                                        JOIN teacher_class t ON c.class_id = t.class_id
                                        WHERE t.teacher_id = ?";
                            $class_stmt = $connect->prepare($class_sql);
                            $class_stmt->bind_param("s", $_SESSION['user_id']);
                            $class_stmt->execute();
                            $class_result = $class_stmt->get_result();
                            
                            while ($class_row = $class_result->fetch_assoc()):
                                $selected = ($class_row['class_id'] == $edit_row['class_id']) ? 'selected' : '';
                            ?>
                            <option value="<?php echo $class_row['class_id']; ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($class_row['class_name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Material File (PDF/DOCX/PPTX):</label>
                        <div class="file-upload">
                            <input type="file" id="material_file" name="material_file" accept=".pdf,.docx,.pptx">
                            <div class="file-info">Current: <?php echo htmlspecialchars($edit_row['file_name']); ?></div>
                            <div id="materialFileError" class="error-message"></div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update_material" class="submit-btn">Save</button>
                        <button type="button" onclick="location.href='lesson_management.php?tab=materials'" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const materialFileInput = document.getElementById('material_file');
            const materialFileInfo = document.querySelector('#editMaterialModal .file-info');
            const materialFileError = document.getElementById('materialFileError');
            
            materialFileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    const fileName = file.name;
                    const fileExt = fileName.split('.').pop().toLowerCase();
                    const allowedTypes = ['pdf', 'docx', 'pptx'];
                    
                    materialFileInfo.textContent = fileName;
                    
                    if (!allowedTypes.includes(fileExt)) {
                        materialFileError.textContent = 'Only PDF, DOCX, and PPTX files are allowed!';
                        materialFileError.style.color = 'red';
                    } else {
                        materialFileError.textContent = '';
                    }
                } else {
                    materialFileInfo.textContent = 'Current: <?php echo htmlspecialchars($edit_row['file_name']); ?>';
                    materialFileError.textContent = '';
                }
            });
            
            document.getElementById('materialEditForm').addEventListener('submit', function(e) {
                if (materialFileInput.files.length > 0) {
                    const file = materialFileInput.files[0];
                    const fileName = file.name;
                    const fileExt = fileName.split('.').pop().toLowerCase();
                    const allowedTypes = ['pdf', 'docx', 'pptx'];
                    
                    if (!allowedTypes.includes(fileExt)) {
                        e.preventDefault();
                        materialFileError.textContent = 'Only PDF, DOCX, and PPTX files are allowed!';
                        materialFileError.style.color = 'red';
                        return;
                    }
                }
                
                if (!confirm('Are you sure you want to save these changes?')) {
                    e.preventDefault();
                }
            });
        });
        </script>
        <?php endif; ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('tab')) {
                    switchTab(urlParams.get('tab'));
                }
                
                document.querySelectorAll('.close-alert').forEach(btn => {
                    btn.addEventListener('click', function() {
                        this.parentElement.style.display = 'none';
                    });
                });
            });
            
            function switchTab(tabName) {
                document.querySelectorAll('.tab').forEach(tab => {
                    tab.classList.remove('active');
                });
                document.querySelectorAll('.content-section').forEach(section => {
                    section.classList.remove('active');
                });
                
                document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
                document.getElementById(tabName).classList.add('active');
                
                const url = new URL(window.location);
                url.searchParams.set('tab', tabName);
                window.history.pushState({}, '', url);
            }
        </script>
    </div>
</body>
</html>