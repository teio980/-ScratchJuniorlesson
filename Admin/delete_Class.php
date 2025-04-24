<?php
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_classes'])) {
        $selectedClasses = $_POST['selected_classes'];
        foreach ($selectedClasses as $classId) {
            $sql = "DELETE FROM class WHERE class_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$classId]);
        }
    }
}
header("Location: manageClass.php");
exit;
?>