<?php
session_start();
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $userId = $_POST['UserID'];
    $username = $_POST["U_Username"];  
    $email = $_POST["U_Email"];
    $password = $_POST["U_Password"];
    $identity = $_POST['identity'];;  

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
        window.location.href = 'manageUser.php';
        </script>";
        exit();
    } elseif ($checkEmailStmt->rowCount() > 0){
        echo "<script>
        alert('Email Exists. Use other email to register.');
        window.location.href = 'manageUser.php';
        </script>";
        exit();
    }
    else{
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE user SET 
        U_Username = ?, 
        U_Password = ?, 
        U_Mail = ?, 
        identity = ? 
        WHERE U_ID = ?";

        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$username, $hashedPassword, $email, $identity, $userId]);
        echo "<script>
        alert('Register Successful!');
        window.location.href = 'manageUser.php';
        </script>";
        exit();
    }
}
?>