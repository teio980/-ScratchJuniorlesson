<?php
include 'connect_DB.php';

if(isset($_POST["reset_password_button"])){
    $email = $_POST["email"];
    $sql_check_email = "SELECT S_Mail FROM student WHERE S_Mail = ?";
    $stmt_check_email = $pdo->prepare($sql_check_email);
    $stmt_check_email->execute([$email]);
    if ($stmt_check_email->rowCount() > 0){
    $token = bin2hex(random_bytes(16));

    $token_hash = hash("sha256",$token);
    date_default_timezone_set('Asia/Shanghai');
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

    $sql = "UPDATE student SET reset_token = ?, reset_token_expires = ? WHERE S_Mail = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$token_hash, $expiry, $email]);

    $mail = require __DIR__ . "/sendEmail.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body =<<<END
    Click <a href="https://localhost/FYP/-ScratchJuniorlesson/includes/reset_password.php?token=$token">here</a> to reset password.
    END;

    $mail->send();
    }
    echo"Massage Sent";
}else{
    header("Location:../landingpage.html");
    exit;
}
?>