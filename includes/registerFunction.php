<?php
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['U_Avatar']) && $_FILES['U_Avatar']['error'] === UPLOAD_ERR_OK) {  
    $uploadDir = '../Student/Avatar/'; 

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    try {
        $fileName = $_FILES['U_Avatar']['name'];
        $fileTmpName = $_FILES['U_Avatar']['tmp_name'];
        $fileSize = $_FILES['U_Avatar']['size'];
        $fileType = $_FILES['U_Avatar']['type'];
        $fileError = $_FILES['U_Avatar']['error'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileExt = strtolower($fileExtension);

        $username = $_POST["U_Username"];  
        $email = $_POST["U_Email"];
        $password = $_POST["U_Password"];
        $identity = "student";

        $maxFileSize = 5 * 1024 * 1024;
        if ($fileSize > $maxFileSize) {
            echo "<script>
                alert('File Choosen is too big. Please choose file smaller than 5MB.');
                window.location.href = '../register.php';
            </script>";
            exit();
        }

        $max_width = 800;
        $max_height = 600;
        list($width, $height) = getimagesize($fileTmpName);

        $needsResize = ($width > $max_width || $height > $max_height);

        if ($needsResize) {
            $ratio = min($max_width/$width, $max_height/$height);
            $new_width = (int)($width * $ratio);
            $new_height = (int)($height * $ratio);

            switch ($fileExt) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($fileTmpName);
                    break;
                case 'png':
                    $image = imagecreatefrompng($fileTmpName);
                    break;
                default:
                    die("Not Supported File Format");
            }

            $new_image = imagecreatetruecolor($new_width, $new_height);

            if ($fileExt == 'png') {
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
            }

            imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagedestroy($image);
        }

        $checkUsernameSql = "SELECT S_Username FROM student WHERE S_Username = :username
                            UNION ALL
                            SELECT T_Username FROM teacher WHERE T_Username = :username
                            UNION ALL
                            SELECT A_Username FROM admin WHERE A_Username = :username
                            LIMIT 1";
        $checkUsernameStmt = $pdo->prepare($checkUsernameSql);
        $checkUsernameStmt->bindParam(':username', $username);
        $checkUsernameStmt->execute();

        $checkEmailSql = "SELECT S_Mail FROM student WHERE S_Mail = :email
                        UNION ALL
                        SELECT T_Mail FROM teacher WHERE T_Mail = :email
                        UNION ALL
                        SELECT A_Mail FROM admin WHERE A_Mail = :email
                        LIMIT 1";
        $checkEmailStmt = $pdo->prepare($checkEmailSql);
        $checkEmailStmt->bindParam(':email', $email);
        $checkEmailStmt->execute();

        if($checkUsernameStmt->rowCount() > 0){
            echo "<script>
                alert('Username Exist.Please Try With Other Username.');
                window.location.href = '../register.php';
            </script>";
            exit();
        } elseif($checkEmailStmt->rowCount() > 0){
            echo "<script>
                alert('Email Exist.Please Try With Other Email.');
                window.location.href = '../register.php';
            </script>";
            exit();
        }

        $currentYear = date("Y");
        $sql_S_checkQty = "SELECT COUNT(*) FROM student WHERE student_id LIKE :targetID";
        $stmt_S_checkQty = $pdo->prepare($sql_S_checkQty);
        $stmt_S_checkQty->bindValue(':targetID', 'STU' . $currentYear . '%');
        $stmt_S_checkQty->execute();
        $student_Qty = $stmt_S_checkQty->fetchColumn();
        $student_id = 'STU'.$currentYear.str_pad($student_Qty + 1, 6, '0', STR_PAD_LEFT);

        $newFileName = $student_id . '.' . $fileExt;
        $destination = $uploadDir . $newFileName;

        if ($needsResize) {
            switch ($fileExt) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($new_image, $destination, 90);
                    break;
                case 'png':
                    imagepng($new_image, $destination, 9);
                    break;
            }
            imagedestroy($new_image);
        } else {
            move_uploaded_file($fileTmpName, $destination);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertSql = "INSERT INTO student (student_id, S_Username, S_Password, S_Mail, identity) 
                      VALUES (:S_ID, :name, :password, :email, :identity)";
        $insertStmt = $pdo->prepare($insertSql);

        $insertStmt->bindParam(':S_ID', $student_id);
        $insertStmt->bindParam(':name', $username);
        $insertStmt->bindParam(':email', $email);
        $insertStmt->bindParam(':password', $hashedPassword);
        $insertStmt->bindParam(':identity', $identity);
        
        if ($insertStmt->execute()) {
            echo "<script>
                alert('Register Successful!!');
                window.location.href = '../login.php';
            </script>";
        } else {
            if (file_exists($destination)) {
                unlink($destination);
            }
            echo "<script>
                alert('Register Failed! Please Try Again Later.');
                window.location.href = '../register.php';
            </script>";
        }
        exit();
        
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        echo "<script>
            alert('Error Happened. Please Try Again later.');
            window.location.href = '../register.php';
        </script>";
        exit();
    }
}
?>