<?php
session_start();
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    try {
        $username = $_POST["U_Username"];
        $password = $_POST["U_Password"];

        $checkUserSql = "
            SELECT identity , student_id AS U_ID , S_Username AS username, S_Password AS password FROM student WHERE S_Username = :username
            UNION ALL
            SELECT identity , teacher_id AS U_ID , T_Username AS username, T_Password AS password FROM teacher WHERE T_Username = :username
            UNION ALL
            SELECT identity , admin_id AS U_ID , A_Username AS username, A_Password AS password FROM admin WHERE A_Username = :username
            LIMIT 1
        ";

        $checkUserStmt = $pdo->prepare($checkUserSql);
        $checkUserStmt->bindParam(':username', $username);
        $checkUserStmt->execute();
        $user = $checkUserStmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['U_ID'];
                $_SESSION['identity'] = $user['identity'];
                $_SESSION['username'] = $user['username'];
                
                switch ($user['identity']) {
                    case 'student':
                        header('Location: ../Student/Main_page.php');
                        break;
                    case 'teacher':
                        header('Location: ../teacher/Main_page.php');
                        break;
                    case 'admin':
                        header('Location: ../Admin/Main_page.php');
                        break;
                    case 'superadmin':
                        header('Location: ../Admin/Main_page.php');
                        break;
                }
                exit();
            } else {
                echo "<script>
                alert('Invalid Password.Please Try Again.');
                window.location.href = '../login.php';
                </script>";
                exit();
            }
        } else {
            echo "<script>
            alert('Invalid Username.Please Try Again.');
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