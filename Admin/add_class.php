<?php 
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $class_code = $_POST["Class_code"];
    $class_name = $_POST["Class_name"];
    $Teacher = $_POST["Teacher_name"];
    $class_description = $_POST["Class_description"];
    $max_capacity = $_POST["max_capacity"];

    $checkClassCodeSql = "SELECT class_code from class WHERE class_code = :C_C";
    $checkClassCodeStmt = $pdo->prepare($checkClassCodeSql);
    $checkClassCodeStmt->bindParam(":C_C",$class_code);
    $checkClassCodeStmt->execute();

    if ($checkClassCodeStmt->rowCount() > 0) {
        echo "<script>
            alert('Class Code exists! Please Try Again with other Class Code');
            window.location.href = 'manageClass.php';
            </script>";
            exit();
    }

    $getTeacherIdSql = "SELECT teacher_id from teacher WHERE T_Username = :name";
    $getTeacherIdStmt = $pdo->prepare($getTeacherIdSql);
    $getTeacherIdStmt->bindParam(':name', $Teacher);
    $getTeacherIdStmt->execute();
    $teacher_id = $getTeacherIdStmt->fetchColumn();


    $insertClassSql = "INSERT INTO class(class_code, class_name, class_description, max_capacity) VALUES ( :C_code, :C_name, :C_description, :max_capacity)";
    $insertClassStmt = $pdo->prepare($insertClassSql);
    $insertClassStmt->bindParam(':C_code', $class_code);
    $insertClassStmt->bindParam(':C_name', $class_name);
    $insertClassStmt->bindParam(':C_description', $class_description);
    $insertClassStmt->bindParam(':max_capacity', $max_capacity);
    $insertClassStmt->execute();

    $last_id = $pdo->lastInsertId();
    $update_sql = "UPDATE class 
                    SET class_id = CONCAT('CLS', LPAD(?, 6, '0')) 
                    WHERE auto_id = ?";
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->execute([$last_id, $last_id]);

    $select_class_id_sql = "SELECT class_id FROM class WHERE auto_id = ?";
    $select_class_id_stmt = $pdo->prepare($select_class_id_sql);
    $select_class_id_stmt->execute([$last_id]);
    $class_id = $select_class_id_stmt->fetchColumn();
    
    $teacher_class_id = 'TC'.str_pad($last_id, 6, '0', STR_PAD_LEFT);

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
            alert('Class Add Failded! Please Try Again.');
            window.location.href = 'manageClass.php';
            </script>";
            exit();
    }

}
?>