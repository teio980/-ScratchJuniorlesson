<?php
session_start();
include 'header_Admin.php';
include '../includes/connect_DB.php';

$rejectionErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_classes'])) {
    $success = 1;
    $selectedIds = $_POST['selected_classes'];
    $action = '';
    
    if (isset($_POST['approved_btn'])) {
        $action = 'approved';
    } elseif (isset($_POST['rejected_btn'])) {
        $action = 'rejected';
    }
    
    $updateChangeClassSql = "UPDATE student_change_class SET status = :status WHERE student_change_class_id = :id";
    $updateChangeClassStmt = $pdo->prepare($updateChangeClassSql);
    
    $getClassIdSql = "SELECT class_id FROM class WHERE class_code = :C_CODE";
    $getClassIdStmt = $pdo->prepare($getClassIdSql);

    $updateClassSql = "UPDATE student_class SET class_id = :new_C_ID WHERE student_id = :S_ID AND class_id = :Old_C_ID";
    $updateClassStmt = $pdo->prepare($updateClassSql);

    $updateCurCapacitySql = "UPDATE class SET current_capacity = current_capacity + 1 WHERE class_id = :C_ID AND max_capacity > current_capacity;";
    $updateCurCapacityStmt = $pdo->prepare($updateCurCapacitySql);

    foreach ($selectedIds as $combinedValue) {
        list($changeId, $studentId, $new_class_Code, $old_class_Code, $status) = explode('|', $combinedValue);
        

        if($action == 'approved'){

        $getClassIdStmt->execute([':C_CODE'=>$new_class_Code]);
        $new_class_row = $getClassIdStmt->fetch(PDO::FETCH_ASSOC);
        $new_class_id = $new_class_row['class_id'];

        $getClassIdStmt->execute([':C_CODE'=>$old_class_Code]);
        $old_class_row = $getClassIdStmt->fetch(PDO::FETCH_ASSOC);
        $old_class_id = $old_class_row['class_id'];

        

        if($updateCurCapacityStmt->execute([':C_ID'=>$new_class_id])){
            $updateClassStmt->execute([
                        ':new_C_ID' => $new_class_id,
                        ':S_ID' => $studentId,
                        ':Old_C_ID' => $old_class_id
                    ]);

            $updateChangeClassStmt->execute([
                        ':status' => $action,
                        ':id' => $changeId
                    ]);
        }

        }else if ($action == 'rejected') {
            if($status == 'approved'){
                $rejectionErrors[] = $studentId;
                continue;
            }else{
            $updateChangeClassStmt->execute([
                ':status' => $action,
                ':id' => $changeId
            ]);
            }
        } else {
            $success = 0;
            break;
        }
    }

    if (!empty($rejectionErrors)) {
        $_SESSION['message'] = "Cannot reject already approved requests for student(s): " . implode(", ", $rejectionErrors) . ". \nOther requests were processed successfully.";
    } elseif ($success == 0) {
        $_SESSION['message'] = "Failed to change class. Please Try Again Later!";
    } else {
        $_SESSION['message'] = "Successful to " . ($action == 'approved' ? 'approve' : 'reject') . " class change request(s)!";
    }
    header("Location: manageChangeClass.php");
    exit();
}
$getChangeClassSql = "SELECT student_change_class_id, student_change_class_reason AS reason, student_original_class AS Ori, student_prefer_class AS Prefer, student_id, status FROM student_change_class;";
$getChangeClassStmt = $pdo->prepare($getChangeClassSql);
$getChangeClassStmt->execute();
$ChangeClassData = $getChangeClassStmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    echo "<script>
    alert('$message');
    </script>";
    
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headerAdmin.css">
    <link rel="stylesheet" href="../cssfile/changeClass.css">
    <title>Change Class</title>
</head>
<body>
    <form action="manageChangeClass.php" method="post">
    <table>
        <thead>
            <tr>
                <th>Select</th>
                <th>Student ID</th>
                <th>Original Class</th>
                <th>Preferred Class</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($ChangeClassData)): ?>
                <?php foreach ($ChangeClassData as $row): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_classes[]" value="<?= htmlspecialchars($row['student_change_class_id'].'|'.$row['student_id'].'|'.$row['Prefer'].'|'.$row['Ori'].'|'.$row['status'])?>"></td>
                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['Ori']); ?></td>
                        <td><?php echo htmlspecialchars($row['Prefer']); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No pending change class requests.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <button type="submit" name="approved_btn" value="approved" onclick="return confirmAction('approve')">Approve</button>
    <button type="submit" name="rejected_btn" value="rejected" onclick="return confirmAction('reject')">Reject</button>
    </form>
</body>
<script>
function confirmAction(action) {
    return confirm("Are you sure you want to " + action + " the selected request(s)?");
}
</script>
</html>