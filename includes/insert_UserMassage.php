<?php
ob_start();
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    try {
        $subject = $_POST["U_Massage_Subject"];  
        $email = $_POST["U_Mail"];
        $massage = $_POST["U_Massage"];  

        $insertSql = "INSERT INTO massage (U_Mail , massage_Subject , massage_Content) VALUES ( :email, :subject , :massage )";
        $insertStmt = $pdo->prepare($insertSql);

        $insertStmt->bindParam(':email', $email);
        $insertStmt->bindParam(':subject', $subject);
        $insertStmt->bindParam(':massage', $massage);
        if ($insertStmt->execute()) {
            echo "<script>
                    alert('Massage Successful Submitted!');
                    window.location.href = '../contactUs.html';
                  </script>";
        } else {
            // 使用普通的 alert
            echo "<script>
                    alert('Some Error Happened, Please Try Again Later!');
                  </script>";
        }
        exit;
    } catch (PDOException $e) {
        echo "Connection Failed:" . $e->getMessage();
    }
}
ob_end_flush();
?>