<?php
session_start();
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    try {
        $username = $_POST["U_Username"];  
        $password = $_POST["U_Password"];  

        $checkSql = "SELECT * FROM user WHERE U_Username = :username";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':username', $username);
        $checkStmt->execute();
        $user = $checkStmt->fetch();

        if ($user && password_verify($password, $user['U_Password'])) {
            $_SESSION['user_id'] = $user['U_ID'];
            $_SESSION['username'] = $user['U_Username'];
            $_SESSION['identity'] = $user['identity'];
            switch($user['identity']){
                case 'admin':
                    header('Location: ../Admin/Main_page.php');
                    break;
                case 'teacher':
                    header('Location: ../teacher/Main_page.php');
                    break;
                case 'student':
                    header('Location: ../Student/Main_page.php');
                    break;
            }
        } else {
            echo "";
            echo "<script>
            alert('Invalid username or password.Please Try Again.');
            window.location.href = '../login.php';
            </script>";
            exit();
        }
        
    } catch (PDOException $e) {
        echo "Connection Failed:" . $e->getMessage();
        exit;
    }
}
?>