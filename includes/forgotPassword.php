<?php
include 'connect_DB.php';

if (isset($_POST["reset_password_button"])) {
    $email = $_POST["email"];
    $sql_check_email = "SELECT identity, student_id AS User_id, S_Mail AS User_Mail FROM student WHERE S_Mail = ?
                        UNION ALL
                        SELECT identity, teacher_id AS User_id, T_Mail AS User_Mail FROM teacher WHERE T_Mail = ?
                        UNION ALL
                        SELECT identity, admin_id AS User_id, A_Mail AS User_Mail FROM admin WHERE A_Mail = ?
                        LIMIT 1";
    $stmt_check_email = $pdo->prepare($sql_check_email);
    $stmt_check_email->execute([$email, $email, $email]);

    if ($data = $stmt_check_email->fetch()) {
        $user_identity = $data['identity'];
        $user_id = $data['User_id'];
        $user_mail = $data['User_Mail'];

        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);
        date_default_timezone_set('Asia/Shanghai');
        $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

        if($user_identity == 'student'){
            $sql = "UPDATE student SET reset_token = ?, reset_token_expires = ? WHERE student_id = ? AND S_Mail = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$token_hash, $expiry,$user_id,$user_mail]);
        }elseif($user_identity == 'teacher'){
            $sql = "UPDATE teacher SET reset_token = ?, reset_token_expires = ? WHERE teacher_id = ? AND T_Mail = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$token_hash, $expiry,$user_id,$user_mail]);
        }elseif($user_identity == 'admin'){
            $sql = "UPDATE admin SET reset_token = ?, reset_token_expires = ? WHERE admin_id = ? AND A_Mail = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$token_hash, $expiry,$user_id,$user_mail]);
        }else{
            echo "<script>
                alert('Invalid Password.Please Try Again.');
                window.location.href = '../register.php';
                </script>";
                exit();
        }
        

        $mail = require __DIR__ . "/sendEmail.php";

        $mail->setFrom("noreply@example.com");
        $mail->addAddress($email);
        $mail->Subject = "Password Reset";
        $mail->Body =<<<END
        Click <a href="https://localhost/FYP/-ScratchJuniorlesson/includes/reset_password.php?token=$token&identity=$user_identity">here</a> to reset password.
        END;

        try {
            $mail->send();
            echo "Message sent!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found in the database.";
    }
}
?>