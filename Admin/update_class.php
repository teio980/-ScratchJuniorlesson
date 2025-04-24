<?php 
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST["ClassID"];
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

    $updateClassSql = "UPDATE class SET class_code = ?, class_name = ?, class_description = ?, max_capacity = ? WHERE class_id = ?";
    $updateClassStmt = $pdo->prepare($updateClassSql);
    $updateClassStmt->execute([$class_code,$class_name,$class_description,$max_capacity,$class_id]);

    $updateTeacherClassSql = "UPDATE teacher_class SET teacher_id = ? WHERE class_id = ?";
    $updateTeacherClassStmt = $pdo->prepare($updateTeacherClassSql);
    $updateTeacherClassStmt->execute([$teacher_id,$class_id]);

    if ($updateClassStmt->rowCount() > 0 || $updateTeacherClassStmt->rowCount() > 0) {
        echo "<script>
        alert('Change Successful!');
        window.location.href = 'manageClass.php';
        </script>";
    }else{
        echo "<script>
        alert('Change Failed!');
        window.location.href = 'manageClass.php';
        </script>";
    }
    exit();
}

?>