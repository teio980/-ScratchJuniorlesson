<?php
session_start();
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    try {
        $username = $_POST["U_Username"];  
        $password = $_POST["U_Password"];  

        $checkSql = "SELECT * FROM student WHERE S_Username = :username";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':username', $username);
        $checkStmt->execute();
        $user = $checkStmt->fetch();

        if ($user && password_verify($password, $user['S_Password'])) {
            $_SESSION['user_id'] = $user['S_ID'];
            $_SESSION['username'] = $user['S_Username'];
            header("Location: ../Student/Main_page.php");
            exit;
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