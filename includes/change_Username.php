<?php
include 'connect_DB.php';
session_start();
$identity = $_SESSION['identity'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["new_Username"];
    $mail = $_POST["new_Mail"];
    $student_id = $_POST["student_id"];
    
    if($identity == "student"){
        $url = '../Student/Personal_Profile.php';
        $sql = "UPDATE student SET S_Username = :username, S_Mail = :mail WHERE student_id = :ID";
    }
    elseif($identity == "teacher"){
        $url = '../teacher/Personal_Profile.php';
        $sql = "UPDATE teacher SET T_Username = :username, T_Mail = :mail WHERE teacher_id = :ID";
    }elseif($identity == "admin" || $identity == "superadmin"){
        $url = '../Admin/admin_profile.php';
        $sql = "UPDATE admin SET A_Username = :username, A_Mail = :mail WHERE admin_id = :ID";
    }

    $checkUsernameSql = "SELECT S_Username FROM student WHERE S_Username = :username AND student_id != :U_ID
                        UNION ALL
                        SELECT T_Username FROM teacher WHERE T_Username = :username AND teacher_id != :U_ID
                        UNION ALL
                        SELECT A_Username FROM admin WHERE A_Username = :username AND admin_id != :U_ID
                        LIMIT 1";
    $checkUsernameStmt = $pdo->prepare($checkUsernameSql);
    $checkUsernameStmt->bindParam(':username', $username);
    $checkUsernameStmt->bindParam(':U_ID', $student_id);
    $checkUsernameStmt->execute();

    $checkEmailSql = "SELECT S_Mail FROM student WHERE S_Mail = :email AND student_id != :U_ID
                    UNION ALL
                    SELECT T_Mail FROM teacher WHERE T_Mail = :email AND teacher_id != :U_ID
                    UNION ALL
                    SELECT A_Mail FROM admin WHERE A_Mail = :email AND admin_id != :U_ID
                    LIMIT 1";
    $checkEmailStmt = $pdo->prepare($checkEmailSql);
    $checkEmailStmt->bindParam(':email', $mail);
    $checkEmailStmt->bindParam(':U_ID', $student_id);
    $checkEmailStmt->execute();

    if($checkUsernameStmt->rowCount() > 0){
        echo "<script>
        alert('Username Exists. Please change your username and Try Again.');
        window.location.href = '$url';
        </script>";
        exit();
    } elseif($checkEmailStmt->rowCount() > 0){
        echo "<script>
        alert('Email Exists. Use other email to register.');
        window.location.href = '$url';
        </script>";
        exit();
    }

    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':mail',$mail);
    $stmt->bindParam(':ID',$student_id);
    
    if($stmt->execute()){
        echo "<script>
            alert('Personal Information Sucessful Updated!');
            window.location.href = '$url';
            </script>";
            exit();
    }else{
        echo "<script>
            alert('Personal Information Failed to Updated!\nPlease Try Again Later.');
            window.location.href = '$url';
            </script>";
            exit();
    }
}
?>