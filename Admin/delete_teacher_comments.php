<?php
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_dataset'])) {
        $selected_dataset = $_POST['selected_dataset'];
    
        foreach ($selected_dataset as $id) {
            $sql = "DELETE FROM content_comments WHERE comment_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
        }
    }
}
echo "<script>
    alert('Comments Deleted Successful!');
    window.location.href = 'teacher_comments.php';
    </script>";
    exit();
?>