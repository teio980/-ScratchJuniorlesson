<?php
include 'connect_DB.php';

if(isset($_POST['enroll_btn'])){
    $class_id = $_POST['class_id'];
    $student_id = $_POST['student_id'];
    $current = $_POST['Current'];
    $max = $_POST['Max'];
    date_default_timezone_set('Asia/Shanghai');
    $current_DateTime = date('Y-m-d H:i:s');

    if(($current+1) > $max){
        echo "<script>
        alert('Enroll Failed! Class is already full.');
        window.location.href = '../Student/Main_page.php';
        </script>";
        exit();
    }

    $checkStudentExistsClassSql = "SELECT COUNT(*) FROM student_class WHERE student_id = :S_ID";
    $checkStudentExistsClassStmt = $pdo->prepare($checkStudentExistsClassSql);
    $checkStudentExistsClassStmt->bindParam(":S_ID",$student_id);
    $checkStudentExistsClassStmt->execute();
    $result = $checkStudentExistsClassStmt->fetch(PDO::FETCH_ASSOC);

    if($result['COUNT(*)'] > 0){
        echo "<script>
        alert('Enroll Failed! You can only enroll one class.Please Try Again Later.');
        window.location.href = '../Student/Main_page.php';
        </script>";
        exit();
    }else{
        try {
        $pdo->beginTransaction();

        // Update class capacity
        $update_Cur_Capacity_Sql = "UPDATE class SET current_capacity = current_capacity + 1 WHERE class_id = :C_ID";
        $update_Cur_Capacity_Stmt = $pdo->prepare($update_Cur_Capacity_Sql);
        $update_Cur_Capacity_Stmt->bindParam(':C_ID', $class_id);
        $update_Cur_Capacity_Stmt->execute();

        // Insert into student_class
        $insert_SC_Sql = "INSERT INTO student_class(student_id, class_id, enroll_date) VALUES (:S_ID, :C_ID, :DateTime)";
        $insert_SC_Stmt = $pdo->prepare($insert_SC_Sql);
        $insert_SC_Stmt->bindParam(':S_ID', $student_id);
        $insert_SC_Stmt->bindParam(':C_ID', $class_id);
        $insert_SC_Stmt->bindParam(':DateTime', $current_DateTime);
        $insert_SC_Stmt->execute();

        $last_id = $pdo->lastInsertId();
        $update_sql = "UPDATE student_class 
                       SET student_class_id = CONCAT('SC', LPAD(?, 6, '0'))
                       WHERE auto_id = ?";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$last_id, $last_id]);

        $pdo->commit();

        echo "<script>
        alert('Enrolled Successfully!');
        window.location.href = '../Student/Main_page.php';
        </script>";
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>
        alert('Enroll Failed! Please Try Again Later.');
        window.location.href = '../Student/Main_page.php';
        </script>";
        exit();
    }
    }
}
?>
