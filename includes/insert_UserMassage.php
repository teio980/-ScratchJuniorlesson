<?php
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    try {
        $phoneNumber = $_POST["phoneNumber"];
        $massage = $_POST["U_Massage"];  

        $whatsappText = "New Message from Website:\n\nPhone No.: $phoneNumber\n\nMessage: $massage";
        $whatsappUrl = "https://wa.me/60102648282?text=" . urlencode($whatsappText);
        header("Location: $whatsappUrl");
        exit();
        
    } catch (PDOException $e) {
        echo "Connection Failed:" . $e->getMessage();
    }
}
?>