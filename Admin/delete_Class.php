<?php
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_classes'])) {
        $selectedClasses = $_POST['selected_classes'];
        foreach ($selectedClasses as $classId) {
        $check_C_Sql = "SELECT * FROM student_class WHERE class_id = ?";
        $check_C_Stmt = $pdo->prepare($check_C_Stmt);
        $check_C_Stmt->execute([$class_id]);
        }
        if ($check_C_Stmt->rowCount() > 0) {
            echo "<script>
            alert('You Can not delete the selected class!');
            window.location.href = 'manageClass.php';
            </script>";
            exit();
        } else {
            foreach ($selectedClasses as $classId) {
            $sql = "DELETE FROM class WHERE class_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$classId]);
        }
        }
    }
}
echo "<script>
            alert('Class Deleted Successful!');
            window.location.href = 'manageClass.php';
            </script>";
            exit();
?>