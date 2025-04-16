<?php
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    try {
        $username = $_POST["U_Username"];  
        $email = $_POST["U_Email"];
        $password = $_POST["U_Password"];
        $identity = "student";  

        $checkUsernameSql = "SELECT S_Username FROM student WHERE S_Username = :username
                            UNION ALL
                            SELECT T_Username FROM teacher WHERE T_Username = :username
                            UNION ALL
                            SELECT A_Username FROM admin WHERE A_Username = :username
                            LIMIT 1";
        $checkUsernameStmt = $pdo->prepare($checkUsernameSql);
        $checkUsernameStmt->bindParam(':username', $username);
        $checkUsernameStmt->execute();

        $checkEmailSql = "SELECT S_Mail FROM student WHERE S_Mail = :email
                        UNION ALL
                        SELECT T_Mail FROM teacher WHERE T_Mail = :email
                        UNION ALL
                        SELECT A_Mail FROM admin WHERE A_Mail = :email
                        LIMIT 1";
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

            $currentYear = date("Y");
            $sql_S_checkQty = "SELECT COUNT(*) FROM student WHERE student_id LIKE :targetID";
            $stmt_S_checkQty = $pdo->prepare($sql_S_checkQty);
            $stmt_S_checkQty->bindValue(':targetID', 'STU' . $currentYear . '%');
            $stmt_S_checkQty->execute();
            $student_Qty = $stmt_S_checkQty->fetchColumn();
            $student_id = 'STU'.$currentYear.str_pad($student_Qty + 1, 6, '0', STR_PAD_LEFT);
            
            $insertSql = "INSERT INTO student (student_id, S_Username, S_Password , S_Mail, identity) VALUES ( :S_ID, :name, :password , :email , :identity)";
            $insertStmt = $pdo->prepare($insertSql);

            $insertStmt->bindParam(':S_ID', $student_id);
            $insertStmt->bindParam(':name', $username);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':password', $hashedPassword);
            $insertStmt->bindParam(':identity', $identity);
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