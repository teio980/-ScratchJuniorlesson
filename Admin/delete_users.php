<?php
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_users'])) {
        $selected_users = $_POST['selected_users'];
    
        foreach ($selected_users as $user_data) {
            list($user_id, $identity) = explode('|', $user_data);
            
            if ($identity === 'student') {
                $sql = "DELETE FROM student WHERE student_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id]);
            } elseif ($identity === 'teacher') {
                $sql = "DELETE FROM teacher WHERE teacher_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id]);
        }
    }
    }
}
header("Location: manageUser.php");
exit;
?>