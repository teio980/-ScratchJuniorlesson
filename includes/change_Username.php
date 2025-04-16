<?php
include 'connect_DB.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["new_Username"];
    $mail = $_POST["new_Mail"];
    $student_id = $_POST["student_id"];

    $sql = "UPDATE student SET S_Username = :username, S_Mail = :mail WHERE student_id = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':mail',$mail);
    $stmt->bindParam(':ID',$student_id);
    
    if($stmt->execute()){
        echo "<script>
            alert('Personal Information Sucessful Updated!');
            window.location.href = '../Student/Personal_Profile.php';
            </script>";
            exit();
    }else{
        echo "<script>
            alert('Personal Information Failed to Updated!\nPlease Try Again Later.');
            window.location.href = '../Student/Personal_Profile.php';
            </script>";
            exit();
    }
}
?>