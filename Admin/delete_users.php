<?php
session_start();
include '../includes/connect_DB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_ids'])) {
        $selectedIds = $_POST['selected_ids'];
        
        $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
        $sql = "DELETE FROM user WHERE U_ID IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($selectedIds);
        
        echo "<script>
            alert('Success delete " . $stmt->rowCount() . " users.');
            window.location.href = 'manageUser.php';
        </script>";
        exit;
    }
}

header("Location: manageUser.php");
exit;
?>