<?php
include 'connect_DB.php';

if(isset($_POST['enroll_btn'])){
    $class_id = $_POST['class_id'];
    $student_id = $_POST['student_id'];
    $current = $_POST['Current'];
    $max = $_POST['Max'];
    date_default_timezone_set('Asia/Shanghai');
    $current_DateTime = date('Y-m-d H:i:s');

    $sql_SC_checkQty = "SELECT COUNT(*) FROM student_class";
    $stmt_SC_checkQty = $pdo->prepare($sql_SC_checkQty);
    $stmt_SC_checkQty->execute();
    $SC_Qty = $stmt_SC_checkQty->fetchColumn();
    $SC_id = 'SC'.str_pad($SC_Qty + 1, 6, '0', STR_PAD_LEFT);

    $update_Cur_Capacity_Sql = "UPDATE class SET current_capacity = :cur + 1 WHERE class_id = :C_ID";
    $update_Cur_Capacity_Stmt = $pdo->prepare($update_Cur_Capacity_Sql);
    
    $update_Cur_Capacity_Stmt->bindParam(':cur',$current);
    $update_Cur_Capacity_Stmt->bindParam(':C_ID',$class_id);
    $update_Cur_Capacity_Stmt->execute();

    $insert_SC_Sql = "INSERT INTO student_class(student_class_id, student_id, class_id, enroll_date) VALUES (:SC_ID, :S_ID, :C_ID, :DateTime)";
    $insert_SC_Stmt = $pdo->prepare($insert_SC_Sql);

    $insert_SC_Stmt->bindParam(':SC_ID',$SC_id);
    $insert_SC_Stmt->bindParam(':S_ID',$student_id);
    $insert_SC_Stmt->bindParam(':C_ID',$class_id);
    $insert_SC_Stmt->bindParam(':DateTime',$current_DateTime);

    $insert_SC_Stmt->execute();
    echo "<script>
        alert('Enrolled Successful!');
        window.location.href = '../Student/Main_page.php';
        </script>";
        exit();
}

?>
