<?php
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    try {
        $username = $_POST["U_Username"];  
        $email = $_POST["U_Email"];
        $password = $_POST["U_Password"];  

        $checkUsernameSql = "SELECT S_Username FROM student WHERE S_Username = :username";
        $checkUsernameStmt = $pdo->prepare($checkUsernameSql);
        $checkUsernameStmt->bindParam(':username', $username);
        $checkUsernameStmt->execute();

        $checkEmailSql = "SELECT S_Mail FROM student WHERE S_Mail = :email";
        $checkEmailStmt = $pdo->prepare($checkEmailSql);
        $checkEmailStmt->bindParam(':email', $email);
        $checkEmailStmt->execute();

        if($checkUsernameStmt->rowCount() > 0){
            echo "<script>
            alert('Username Exists. Please change your username and Try Again.');
            window.location.href = '../register.php';
            </script>";
            exit();
        } elseif($checkEmailStmt->rowCount() > 0){
            echo "<script>
            alert('Email Exists. Use other email to register.');
            window.location.href = '../register.php';
            </script>";
            exit();
        }
        else{
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertSql = "INSERT INTO student (S_Username, S_Password ,S_Mail) VALUES ( :name, :password , :email )";
            $insertStmt = $pdo->prepare($insertSql);

            $insertStmt->bindParam(':name', $username);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':password', $hashedPassword);
            $insertStmt->execute();
            echo "<script>
            alert('Register Successful!');
            window.location.href = '../login.php';
            </script>";
            exit();
        }
        
    } catch (PDOException $e) {
        echo "Connection Failed:" . $e->getMessage();
    }
}

?>