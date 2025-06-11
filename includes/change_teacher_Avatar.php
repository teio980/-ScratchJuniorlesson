    <?php
    session_start();
    include 'connect_DB.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {  
        $student_id = $_SESSION['user_id'];
        $uploadDir = '../teacher/Avatar/'; 

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if(isset($_FILES['change_Avatar']) && $_FILES['change_Avatar']['error'] === UPLOAD_ERR_OK){
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $fileName = $_FILES['change_Avatar']['name'];
            $fileTmpName = $_FILES['change_Avatar']['tmp_name'];
            $fileSize = $_FILES['change_Avatar']['size'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileExt = strtolower($fileExtension);
            if (!in_array($fileExt, $allowedExtensions)) {
                    echo "<script>
                        alert('Only JPG, JPEG, and PNG files are allowed.');
                        window.location.href = '../teacher/teacher_profile.php';
                    </script>";
                    exit();
            }

            $maxFileSize = 5 * 1024 * 1024;
            if ($fileSize > $maxFileSize) {
                echo "<script>
                    alert('File Choosen is too big. Please choose file smaller than 5MB.');
                    window.location.href = '../teacher/teacher_profile.php';
                </script>";
                exit();
            }
            $oldFiles = glob($uploadDir . $student_id . '.*'); 
            foreach ($oldFiles as $oldFile) {
                if (is_file($oldFile)) {
                    unlink($oldFile); 
                }
            }

            $newFileName = $student_id . '.' . $fileExt;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpName, $destination)) {
                echo "<script>
                    alert('Avatar updated successfully!');
                    window.location.href = '../teacher/teacher_profile.php';
                </script>";
            } else {
                echo "<script>
                    alert('Failed to upload avatar. Please try again.');
                    window.location.href = '../teacher/teacher_profile.php';
                </script>";
            }
        }
    }
    ?>