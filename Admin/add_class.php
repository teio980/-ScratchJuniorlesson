<?php 
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $class_code = $_POST["Class_code"];
    $class_name = $_POST["Class_name"];
    $Teacher = $_POST["Teacher_name"];
    $class_description = $_POST["Class_description"];
    $max_capacity = $_POST["max_capacity"];

    $getTeacherIdSql = "SELECT teacher_id from teacher WHERE T_Username = :name";
    $getTeacherIdStmt = $pdo->prepare($getTeacherIdSql);
    $getTeacherIdStmt->bindParam(':name', $Teacher);
    $getTeacherIdStmt->execute();
    $teacher_id = $getTeacherIdStmt->fetchColumn();

    $sql_C_checkQty = "SELECT COUNT(*) FROM class";
    $stmt_C_checkQty = $pdo->prepare($sql_C_checkQty);
    $stmt_C_checkQty->execute();
    $class_Qty = $stmt_C_checkQty->fetchColumn();
    $class_id = 'CLS'.str_pad($class_Qty + 1, 6, '0', STR_PAD_LEFT);

    $insertClassSql = "INSERT INTO class(class_id, class_code, class_name, class_description, max_capacity) VALUES (:C_ID, :C_code, :C_name, :C_description, :max_capacity)";
    $insertClassStmt = $pdo->prepare($insertClassSql);
    $insertClassStmt->bindParam(':C_ID', $class_id);
    $insertClassStmt->bindParam(':C_code', $class_code);
    $insertClassStmt->bindParam(':C_name', $class_name);
    $insertClassStmt->bindParam(':C_description', $class_description);
    $insertClassStmt->bindParam(':max_capacity', $max_capacity);
    $insertClassStmt->execute();

    $sql_T_C_checkQty = "SELECT COUNT(*) FROM teacher_class";
    $stmt_T_C_checkQty = $pdo->prepare($sql_T_C_checkQty);
    $stmt_T_C_checkQty->execute();
    $teacher_class_Qty = $stmt_T_C_checkQty->fetchColumn();
    $teacher_class_id = 'TC'.str_pad($class_Qty + 1, 6, '0', STR_PAD_LEFT);

    $insertTeacherClassSql = "INSERT INTO teacher_class(teacher_class_id, teacher_id, class_id) VALUES (:T_C_ID, :T_ID, :C_ID)";
    $insertTeacherClassStmt = $pdo->prepare($insertTeacherClassSql);
    $insertTeacherClassStmt->bindParam(':T_C_ID', $teacher_class_id);
    $insertTeacherClassStmt->bindParam(':C_ID', $class_id);
    $insertTeacherClassStmt->bindParam(':T_ID', $teacher_id);
    

    if($insertTeacherClassStmt->execute()){
        echo "<script>
            alert('Class Added Successful!');
            window.location.href = 'manageClass.php';
            </script>";
            exit();
    }else{
        echo "<script>
            alert('Class Add Failde! Please Try Again.');
            window.location.href = 'manageClass.php';
            </script>";
            exit();
    }

}
?>