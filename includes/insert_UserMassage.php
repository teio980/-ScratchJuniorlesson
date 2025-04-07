<?php
ob_start();
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    try {
        $phoneNumber = $_POST["phoneNumber"];
        $massage = $_POST["U_Massage"];  

        $insertSql = "INSERT INTO massage (U_phoneNumber , massage_Content) VALUES ( :phoneNumber, :massage )";
        $insertStmt = $pdo->prepare($insertSql);

        $insertStmt->bindParam(':phoneNumber', $phoneNumber);
        $insertStmt->bindParam(':massage', $massage);
        if ($insertStmt->execute()) {
            $whatsappText = "New Message from Website:\n\nPhone No.: $phoneNumber\n\nMessage: $massage";
            $whatsappUrl = "https://wa.me/60102648282?text=" . urlencode($whatsappText);
            header("Location: $whatsappUrl");
            exit();
        } else {
            echo "<script>
                    alert('Some Error Happened, Please Try Again Later!');
                  </script>";
            header("Location: ../contactUs.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Connection Failed:" . $e->getMessage();
    }
}
ob_end_flush();
?>