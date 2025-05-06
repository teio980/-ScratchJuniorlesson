<?php
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['S_ID'];
    $oldClass = $_POST['old_class'];
    $newClass = $_POST['class_option'];
    $reason = $_POST['changeClassReason'];


    $insert_SCC_Sql = "INSERT INTO student_change_class(student_change_class_reason,student_original_class,student_prefer_class,student_id)
                       VALUES (:reason, :oldClass, :newClass, :S_ID)";
    $insert_SCC_Stmt = $pdo->prepare($insert_SCC_Sql);
    $insert_SCC_Stmt->bindParam(":reason",$reason);
    $insert_SCC_Stmt->bindParam(":oldClass",$oldClass);
    $insert_SCC_Stmt->bindParam(":newClass",$newClass);
    $insert_SCC_Stmt->bindParam(":S_ID",$student_id);

    if($insert_SCC_Stmt->execute()){
        $last_id = $pdo->lastInsertId();
        $update_sql = "UPDATE student_change_class 
                        SET student_change_class_id = CONCAT('SCC', LPAD(?, 8, '0'))
                        WHERE auto_id = ?";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$last_id, $last_id]);

        echo "<script>
            alert('Request Successful to Send.\nWaiting For Admin Process.');
            window.location.href = '../Student/Main_page.php';
            </script>";
        exit();
    }
    else{
        echo "<script>
            alert('Request Failed to Send.\nPlease Try Again Later.');
            window.location.href = '../Student/Main_page.php';
            </script>";
            exit();
    }
}

?>