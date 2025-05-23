<?php
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $userId = $_POST['UserID'];
    $username = $_POST["U_Username"];  
    $email = $_POST["U_Email"];
    $identity = $_POST['Identity'];

    $checkUsernameSql = "SELECT S_Username FROM student WHERE S_Username = :username AND student_id != :userId
                        UNION ALL
                        SELECT T_Username FROM teacher WHERE T_Username = :username AND teacher_id != :userId
                        UNION ALL
                        SELECT A_Username FROM admin WHERE A_Username = :username AND admin_id != :userId
                        LIMIT 1";
    $checkUsernameStmt = $pdo->prepare($checkUsernameSql);
    $checkUsernameStmt->bindParam(':username', $username);
    $checkUsernameStmt->bindParam(':userId', $userId);
    $checkUsernameStmt->execute();

    $checkEmailSql = "SELECT S_Mail FROM student WHERE S_Mail = :email AND student_id != :userId
                    UNION ALL
                    SELECT T_Mail FROM teacher WHERE T_Mail = :email AND teacher_id != :userId
                    UNION ALL
                    SELECT A_Mail FROM admin WHERE A_Mail = :email AND admin_id != :userId
                    LIMIT 1";
    $checkEmailStmt = $pdo->prepare($checkEmailSql);
    $checkEmailStmt->bindParam(':email', $email);
    $checkEmailStmt->bindParam(':userId', $userId);
    $checkEmailStmt->execute();

    if($checkUsernameStmt->rowCount() > 0){
        echo "<script>
        alert('Username Exists. Please Try Again.');
        window.location.href = '../Admin/addUser.php';
        </script>";
        exit();
    } elseif($checkEmailStmt->rowCount() > 0){
        echo "<script>
        alert('Email Exists. Please Try Again.');
        window.location.href = '../Admin/addUser.php';
        </script>";
        exit();
    }
    
    
    $getPasswordSql = null;
    switch ($identity) {
        case 'student':
            $getPasswordSql = "SELECT S_Password FROM student WHERE student_id = ?";
            break;
        case 'teacher':
            $getPasswordSql = "SELECT T_Password FROM teacher WHERE teacher_id = ?";
            break;
        case 'admin':
            $getPasswordSql = "SELECT A_Password FROM admin WHERE admin_id = ?";
            break;
    }
    
    $stmt = $pdo->prepare($getPasswordSql);
    $stmt->execute([$userId]);
    $password = $stmt->fetchColumn();
    

    switch ($identity) {
        case 'student':
            $updateSql = "UPDATE student SET S_Username = ?, S_Mail = ?, S_Password =? WHERE student_id = ?";
            break;
        case 'teacher':
            $updateSql = "UPDATE teacher SET T_Username = ?, T_Mail = ?, T_Password =? WHERE teacher_id = ?";
            break;
        case 'admin':
            $updateSql = "UPDATE admin SET A_Username = ?, A_Mail = ?, A_Password =? WHERE admin_id = ?";
            break;
    }
    
    $stmt = $pdo->prepare($updateSql);
    $stmt->execute([$username, $email, $password, $userId]);

    if ($stmt->rowCount() > 0) {
        echo "<script>
        alert('Change Successful!');
        window.location.href = 'manageUser.php';
        </script>";
    }else{
        echo "<script>
        alert('Change Failed!');
        window.location.href = 'manageUser.php';
        </script>";
    }
    exit();
}
?>