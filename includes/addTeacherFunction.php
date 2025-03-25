<?php
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    try {
        $username = $_POST["U_Username"];  
        $email = $_POST["U_Email"];
        $password = $_POST["U_Password"];
        $identity = "teacher";  

        $checkUsernameSql = "SELECT U_Username FROM user WHERE U_Username = :username";
        $checkUsernameStmt = $pdo->prepare($checkUsernameSql);
        $checkUsernameStmt->bindParam(':username', $username);
        $checkUsernameStmt->execute();

        $checkEmailSql = "SELECT U_Mail FROM user WHERE U_Mail = :email";
        $checkEmailStmt = $pdo->prepare($checkEmailSql);
        $checkEmailStmt->bindParam(':email', $email);
        $checkEmailStmt->execute();

        if($checkUsernameStmt->rowCount() > 0){
            echo "<script>
            alert('Username Exists. Please change your username and Try Again.');
            window.location.href = '../Admin/addTeacher.php';
            </script>";
            exit();
        } elseif($checkEmailStmt->rowCount() > 0){
            echo "<script>
            alert('Email Exists. Use other email to register.');
            window.location.href = '../Admin/addTeacher.php';
            </script>";
            exit();
        }
        else{
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertSql = "INSERT INTO user (U_Username, U_Password , U_Mail, identity) VALUES ( :name, :password , :email , :identity)";
            $insertStmt = $pdo->prepare($insertSql);

            $insertStmt->bindParam(':name', $username);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':password', $hashedPassword);
            $insertStmt->bindParam(':identity', $identity);
            $insertStmt->execute();
            echo "<script>
            alert('Register Successful!');
            window.location.href = '../Admin/addTeacher.php';
            </script>";
            exit();
        }
        
    } catch (PDOException $e) {
        echo "Connection Failed:" . $e->getMessage();
    }
}

?>