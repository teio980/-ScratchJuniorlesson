<?php
session_start();
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $userId = $_POST['UserID'];
    $username = $_POST["U_Username"];  
    $email = $_POST["U_Email"];
    $identity = $_POST['identity'];;  

    $checkUsernameSql = "SELECT U_Username FROM user WHERE U_Username = :username AND U_ID != :userID";
    $checkUsernameStmt = $pdo->prepare($checkUsernameSql);
    $checkUsernameStmt->bindParam(':username', $username);
    $checkUsernameStmt->bindParam(':userID', $userId);
    $checkUsernameStmt->execute();

    $checkEmailSql = "SELECT U_Mail FROM user WHERE U_Mail = :email AND U_ID != :userID";
    $checkEmailStmt = $pdo->prepare($checkEmailSql);
    $checkEmailStmt->bindParam(':email', $email);
    $checkEmailStmt->bindParam(':userID', $userId);
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

        $sql = "UPDATE user SET 
        U_Username = ?, 
        U_Mail = ?, 
        identity = ? 
        WHERE U_ID = ?";

        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$username, $email, $identity, $userId]);
        echo "<script>
        alert('Change Successful!');
        window.location.href = 'manageUser.php';
        </script>";
        exit();
    }
}
?>