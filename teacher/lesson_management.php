<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';
include 'resheadteacher.php';

// 处理lesson删除
if (isset($_GET['id'])) {
    $lesson_id = $_GET['id'];
    
    // 获取文件信息
    $sql = "SELECT file_name, thumbnail_name FROM lessons WHERE lesson_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $lesson = $result->fetch_assoc();
    
    // 删除记录
    $delete_sql = "DELETE FROM lessons WHERE lesson_id = ?";
    $delete_stmt = $connect->prepare($delete_sql);
    $delete_stmt->bind_param("s", $lesson_id);
    
    if ($delete_stmt->execute()) {
        // 删除文件
        $lesson_file_path = "../phpfile/uploads/lesson/" . $lesson['file_name'];
        $thumbnail_path = "../phpfile/uploads/thumbnail/" . $lesson['thumbnail_name'];
        
        if (file_exists($lesson_file_path)) unlink($lesson_file_path);
        if (file_exists($thumbnail_path)) unlink($thumbnail_path);
        
        $_SESSION['success'] = "Lesson deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete lesson!";
    }
    header("Location: lesson_management.php");
    exit();
}

// 处理material删除
if (isset($_GET['delete_material'])) {
    $material_id = $_GET['delete_material'];
    
    // 获取文件信息
    $sql = "SELECT file_name FROM teacher_materials WHERE material_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $material = $result->fetch_assoc();
    
    // 删除记录
    $delete_sql = "DELETE FROM teacher_materials WHERE material_id = ?";
    $delete_stmt = $connect->prepare($delete_sql);
    $delete_stmt->bind_param("s", $material_id);
    
    if ($delete_stmt->execute()) {
        // 删除文件
        $file_path = "../phpfile/upload_teacher_material/" . $material['file_name'];
        if (file_exists($file_path)) unlink($file_path);
        
        $_SESSION['success'] = "Material deleted successfully!";
    }
    header("Location: lesson_management.php?tab=materials");
    exit();
}

// 处理lesson更新部分修改
if (isset($_POST['update'])) {
    $lesson_id = $_POST['lesson_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // 获取当前文件信息
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
    
    // 处理文件更新
    if (!empty($_FILES['lesson_file']['name'])) {
        // 删除旧文件
        $old_file_path = "../phpfile/uploads/lesson/" . $old_file_name;
        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }
        
        // 生成新文件名
        $original_filename = basename($_FILES['lesson_file']['name']);
        $new_file_name = generateUniqueFilename("../phpfile/uploads/lesson/", $original_filename);
        move_uploaded_file($_FILES['lesson_file']['tmp_name'], "../phpfile/uploads/lesson/" . $new_file_name);
    }
    
    if (!empty($_FILES['thumbnail']['name'])) {
        // 删除旧缩略图
        $old_thumb_path = "../phpfile/uploads/thumbnail/" . $old_thumbnail_name;
        if (file_exists($old_thumb_path)) {
            unlink($old_thumb_path);
        }
        
        // 生成新缩略图文件名
        $original_thumbnail = basename($_FILES['thumbnail']['name']);
        $new_thumbnail_name = generateUniqueFilename("../phpfile/uploads/thumbnail/", $original_thumbnail);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], "../phpfile/uploads/thumbnail/" . $new_thumbnail_name);
    }
    
    // 更新数据库
    $update_sql = "UPDATE lessons SET 
                  title = ?, 
                  description = ?, 
                  file_name = ?, 
                  thumbnail_name = ? 
                  WHERE lesson_id = ?";
                  
    $stmt = $connect->prepare($update_sql);
    $stmt->bind_param("sssss", $title, $description, $new_file_name, $new_thumbnail_name, $lesson_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Lesson updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update lesson!";
    }
    header("Location: lesson_management.php");
    exit();
}

// 处理material更新
if (isset($_POST['update_material'])) {
    $material_id = $_POST['material_id'];
    $title = $_POST['material_title'];
    $description = $_POST['material_description'];
    $class_id = $_POST['class_id'];
    
    // 获取当前文件信息
    $sql = "SELECT file_name FROM teacher_materials WHERE material_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $material = $result->fetch_assoc();
    
    $old_file_name = $material['file_name'];
    $new_file_name = $old_file_name;
    
    // 处理文件更新
    if (!empty($_FILES['material_file']['name'])) {
        // 删除旧文件
        $old_file_path = "../phpfile/upload_teacher_material/" . $old_file_name;
        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }
        
        // 生成新文件名
        $original_filename = basename($_FILES['material_file']['name']);
        $new_file_name = str_replace(' ', '_', $original_filename);
        move_uploaded_file($_FILES['material_file']['tmp_name'], "../phpfile/upload_teacher_material/" . $new_file_name);
    }
    
    // 更新数据库
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
        
        <!-- 选项卡导航 -->
        <div class="tab-container">
            <div class="tab active" onclick="switchTab('lessons')">Lessons</div>
            <div class="tab" onclick="switchTab('materials')">Materials</div>
        </div>
        
        <!-- 消息提示 -->
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
        
        <!-- Lessons Section -->
        <div id="lessons" class="content-section active">
            <div class="lesson-cards-container">
                <div class="add-lesson-card" onclick="location.href='upload_lesson.php'">
                    <div class="add-lesson-icon">+</div>
                    <div class="add-lesson-text">Add New Lesson</div>
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
        
        <!-- Materials Section -->
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

        <!-- Lesson Edit Modal -->
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
                        <label for="thumbnail">Thumbnail:</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        <div class="file-info">Current: <?php echo htmlspecialchars($edit_row['thumbnail_name']); ?></div>
                    </div>

                    <div class="form-group">
                        <label for="lesson_file">Lesson File:</label>
                        <input type="file" id="lesson_file" name="lesson_file" accept=".pdf,.doc,.docx">
                        <div class="file-info">Current: <?php echo htmlspecialchars($edit_row['file_name']); ?></div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update" class="submit-btn" onclick="return confirm('Save changes?')">Save</button>
                        <button type="button" onclick="location.href='lesson_management.php'" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Material Edit Modal -->
        <?php if (isset($_GET['edit_material_id'])):
            $edit_material_id = $_GET['edit_material_id'];
            $edit_sql = "SELECT * FROM teacher_materials WHERE material_id = ?";
            $edit_stmt = $connect->prepare($edit_sql);
            $edit_stmt->bind_param("s", $edit_material_id);
            $edit_stmt->execute();
            $edit_result = $edit_stmt->get_result();
            $edit_row = $edit_result->fetch_assoc();
        ?>
        <div id="editMaterialModal" class="modal" style="display: block;">
            <div class="modal-content">
                <span class="close" onclick="location.href='lesson_management.php?tab=materials'">&times;</span>
                <h2>Edit Material</h2>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="material_id" value="<?php echo $edit_row['material_id']; ?>">
                    
                    <div class="form-group">
                        <label for="material_title">Title:</label>
                        <input type="text" id="material_title" name="material_title" value="<?php echo htmlspecialchars($edit_row['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="material_description">Description:</label>
                        <textarea id="material_description" name="material_description" required><?php echo htmlspecialchars($edit_row['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="class_id">Class:</label>
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
                        <label for="material_file">Material File:</label>
                        <input type="file" id="material_file" name="material_file" accept=".pdf,.docx,.pptx">
                        <div class="file-info">Current: <?php echo htmlspecialchars($edit_row['file_name']); ?></div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="update_material" class="submit-btn" onclick="return confirm('Save changes?')">Save</button>
                        <button type="button" onclick="location.href='lesson_management.php?tab=materials'" class="cancel-btn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('tab')) {
                    switchTab(urlParams.get('tab'));
                }
                
                // 关闭提示框
                document.querySelectorAll('.close-alert').forEach(btn => {
                    btn.addEventListener('click', function() {
                        this.parentElement.style.display = 'none';
                    });
                });
            });
            
            // 选项卡切换
            function switchTab(tabName) {
                document.querySelectorAll('.tab').forEach(tab => {
                    tab.classList.remove('active');
                });
                document.querySelectorAll('.content-section').forEach(section => {
                    section.classList.remove('active');
                });
                
                document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
                document.getElementById(tabName).classList.add('active');
                
                // 更新URL但不刷新页面
                const url = new URL(window.location);
                url.searchParams.set('tab', tabName);
                window.history.pushState({}, '', url);
            }
        </script>
    </div>
</body>
</html>