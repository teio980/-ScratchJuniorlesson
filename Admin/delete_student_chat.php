<?php
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_dataset'])) {
        $selected_dataset = $_POST['selected_dataset'];
    
        foreach ($selected_dataset as $id) {
            $sql = "DELETE FROM student_livechat WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
        }
    }
}
echo "<script>
    alert('Chat Record Deleted Successful!');
    window.location.href = 'student_livechat.php';
    </script>";
    exit();
?>