<?php
session_start();
include 'connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['S_ID'];
    $oldClass = $_POST['old_class'];
    $newClass = $_POST['class_option'];
    $reason = $_POST['changeClassReason'];

    $check_S_Progress_Sql = "SELECT SC.enroll_date 
                            FROM student_class AS SC
                            JOIN class AS C ON C.class_id = SC.class_id
                            WHERE SC.student_id = :S_ID
                            AND C.class_code = :C_CODE";
    $check_S_Progress_Stmt = $pdo->prepare($check_S_Progress_Sql);
    $check_S_Progress_Stmt->bindParam(":S_ID",$student_id);
    $check_S_Progress_Stmt->bindParam(":C_CODE",$oldClass);
    $check_S_Progress_Stmt->execute();
    $prevEnrollDate = $check_S_Progress_Stmt->fetch(PDO::FETCH_ASSOC);

    $db_date = new DateTime($prevEnrollDate['enroll_date']);
    $now = new DateTime(); 
    $db_plus_one_month = clone($db_date);
    $db_plus_one_month->add(new DateInterval('P1M'));
    if ($now <= $db_plus_one_month) {
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

            $_SESSION['message'] = 'Request Successful to Send. Waiting For Admin Process.';
            $_SESSION['message_type'] = 'success';
            header("Location: ../Student/Main_page.php");
            exit();
        }
        else{
            $_SESSION['message'] = 'Request Failed to Send. Please Try Again Later.';
            $_SESSION['message_type'] = 'error';
            header("Location: ../Student/Main_page.php");
            exit();
        }
    }else{
        $_SESSION['message'] = 'You cannot change the class now.';
        $_SESSION['message_type'] = 'error';
        header("Location: ../Student/Main_page.php");
        exit();
    }
}

?>