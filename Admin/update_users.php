<?php
session_start();
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $userId = $_POST['UserID'];
    $username = $_POST["U_Username"];  
    $email = $_POST["U_Email"];
    $identity = $_POST['identity'];;  

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
    
    $currentIdentitySql = "SELECT identity FROM (
            SELECT student_id AS id, identity FROM student WHERE student_id = :userId
            UNION ALL
            SELECT teacher_id AS id, identity FROM teacher WHERE teacher_id = :userId
            UNION ALL
            SELECT admin_id AS id, identity FROM admin WHERE admin_id = :userId
        ) AS combined LIMIT 1";

    $stmt = $pdo->prepare($currentIdentitySql);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $currentIdentity = $stmt->fetchColumn();

    if ($currentIdentity != $identity) {
            $getPasswordSql = "";
            switch ($currentIdentity) {
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

            switch ($currentIdentity) {
            case 'student':
            $deleteSql = "DELETE FROM student WHERE student_id = ?";
            break;
            case 'teacher':
            $deleteSql = "DELETE FROM teacher WHERE teacher_id = ?";
            break;
            case 'admin':
            $deleteSql = "DELETE FROM admin WHERE admin_id = ?";
            break;
            }
        $stmt = $pdo->prepare($deleteSql);
        $stmt->execute([$userId]);
    }

    switch ($identity) {
        case 'student':
            $insertSql = "INSERT INTO student (student_id, S_Username, S_Mail, S_Password, identity) 
                    VALUES (?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE 
                    S_Username = VALUES(S_Username), 
                    S_Mail = VALUES(S_Mail), 
                    S_Password = VALUES(S_Password),
                    identity = VALUES(identity)";
            break;
        case 'teacher':
            $insertSql = "INSERT INTO teacher (teacher_id, T_Username, T_Mail, T_Password, identity) 
                    VALUES (?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE 
                    T_Username = VALUES(T_Username), 
                    T_Mail = VALUES(T_Mail), 
                    T_Password = VALUES(T_Password),
                    identity = VALUES(identity)";
            break;
        case 'admin':
            $insertSql = "INSERT INTO admin (admin_id, A_Username, A_Mail, A_Password, identity) 
                    VALUES (?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE 
                    A_Username = VALUES(A_Username), 
                    A_Mail = VALUES(A_Mail), 
                    A_Password = VALUES(A_Password),
                    identity = VALUES(identity)";
            break;
    }
    
    $stmt = $pdo->prepare($insertSql);
    $stmt->execute([$userId, $username, $email, $password, $identity]);
    echo "<script>
    alert('Change Successful!');
    window.location.href = 'manageUser.php';
    </script>";
    exit();
        
}
?>