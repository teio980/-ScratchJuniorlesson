<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';
include 'resheadteacher.php';

if (!isset($_SESSION['user_id'])) {
    echo "You need to be logged in as a teacher to upload materials.";
    exit;
}

$teacher_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id = $_POST['class_id'];
    $material_title = htmlspecialchars($_POST['material_title']);
    $material_description = htmlspecialchars($_POST['material_description']);
    $file = $_FILES['material_file'];

    $target_dir = "../phpfile/upload_teacher_material/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = str_replace(' ', '_', basename($file["name"]));
    $target_file = $target_dir . $file_name;

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = array("pdf", "docx", "pptx");

    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $sql = "INSERT INTO teacher_materials (teacher_id, class_id, title, description, file_name)
                    VALUES ('$teacher_id', '$class_id', '$material_title', '$material_description', '$file_name')";

            if (mysqli_query($connect, $sql)) {
                $_SESSION['success'] = "Material uploaded successfully!";
                header("Location: lesson_management.php?tab=materials");
                exit();
            } else {
                $_SESSION['error'] = "Database error: " . mysqli_error($connect);
            }
        } else {
            $_SESSION['error'] = "Sorry, there was an error uploading your file.";
        }
    } else {
        $_SESSION['error'] = "Invalid file type. Only PDF, DOCX, and PPTX files are allowed.";
    }
    header("Location: upload_teacher_material.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Teacher Material</title>
    <link rel="stylesheet" href="../cssfile/Teachermain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/lesson_management.css">
    <style>
        .upload-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .file-upload {
            margin-top: 5px;
        }
        .file-info {
            margin-top: 5px;
            font-size: 0.9em;
            color: #666;
        }
        .error-message {
            color: red;
            margin-top: 5px;
        }
        .form-actions {
            margin-top: 20px;
            text-align: right;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        .btn-secondary {
            background-color: #f44336;
            color: white;
            margin-left: 10px;
        }
        .required-field:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Teaching Material</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-box error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <span class="close-alert">&times;</span>
            </div>
        <?php endif; ?>
        
        <div class="upload-container">
            <form method="POST" enctype="multipart/form-data" id="materialUploadForm">
                <!-- Select Class -->
                <div class="form-group">
                    <label for="class_id" class="required-field">Class:</label>
                    <select name="class_id" id="class_id" required>
                        <option value="">Select a class</option>
                        <?php
                        $class_sql = "SELECT c.class_id, c.class_name FROM class c
                                    JOIN teacher_class t ON c.class_id = t.class_id
                                    WHERE t.teacher_id = '$teacher_id'";
                        $class_result = mysqli_query($connect, $class_sql);
                        while ($class_row = mysqli_fetch_assoc($class_result)) {
                            echo "<option value='" . $class_row['class_id'] . "'>" . $class_row['class_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Material Title -->
                <div class="form-group">
                    <label for="material_title" class="required-field">Title:</label>
                    <input type="text" name="material_title" id="material_title" required>
                </div>

                <!-- Material Description -->
                <div class="form-group">
                    <label for="material_description" class="required-field">Description:</label>
                    <textarea name="material_description" id="material_description" required rows="4"></textarea>
                </div>

                <!-- File Upload -->
                <div class="form-group">
                    <label class="required-field">Material File (PDF/DOCX/PPTX):</label>
                    <div class="file-upload">
                        <input type="file" name="material_file" id="material_file" required accept=".pdf,.docx,.pptx">
                        <div class="file-info">No file chosen</div>
                        <div id="fileError" class="error-message"></div>
                    </div>
                </div>

                <!-- Form actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" onclick="window.location.href='lesson_management.php?tab=materials'" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('material_file');
        const fileInfo = document.querySelector('.file-info');
        
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                const fileName = file.name;
                const fileExt = fileName.split('.').pop().toLowerCase();
                const allowedTypes = ['pdf', 'docx', 'pptx'];
                
                fileInfo.textContent = fileName;
                
                if (!allowedTypes.includes(fileExt)) {
                    alert('Only PDF, DOCX, and PPTX files are allowed!');
                    this.value = ''; // Clear the file input
                    fileInfo.textContent = 'No file chosen';
                }
            } else {
                fileInfo.textContent = 'No file chosen';
            }
        });
        
        // Form submission validation
        document.getElementById('materialUploadForm').addEventListener('submit', function(e) {
            if (fileInput.files.length === 0) {
                e.preventDefault();
                alert('Please select a file to upload');
                return;
            }
            
            const file = fileInput.files[0];
            const fileName = file.name;
            const fileExt = fileName.split('.').pop().toLowerCase();
            const allowedTypes = ['pdf', 'docx', 'pptx'];
            
            if (!allowedTypes.includes(fileExt)) {
                e.preventDefault();
                alert('Only PDF, DOCX, and PPTX files are allowed!');
                return;
            }
            
            if (!confirm('Are you sure you want to upload this material?')) {
                e.preventDefault();
            }
        });
        
        // Close alert box
        document.querySelector('.close-alert')?.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });
    </script>
</body>
</html>