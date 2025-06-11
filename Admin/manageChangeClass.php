<?php
session_start();
include 'header_Admin.php';
include '../includes/connect_DB.php';
date_default_timezone_set('Asia/Singapore');
if (isset($_GET['query'])) {
    $_SESSION['search_query'] = $_GET['query'];
} elseif (!isset($_SESSION['search_query'])) {
    $_SESSION['search_query'] = '';
}

if (isset($_GET['clear_search'])) {
    $_SESSION['search_query'] = '';
}

$keywords = $_SESSION['search_query'];

// Pagination settings
if (isset($_GET['limit'])) {
    if ($_GET['limit'] == 'ALL') {
        $records_per_page = 'ALL';
    } else {
        $records_per_page = (int)$_GET['limit'];
    }
} else {
    $records_per_page = 15; 
}

if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
    if ($page < 1) {
        $page = 1;
    }
} else {
    $page = 1;
}

if ($records_per_page === 'ALL') {
    $start = 0;
} else {
    $start = ($page - 1) * $records_per_page;
}

$rejectionErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_classes'])) {
    $success = 1;
    $selectedIds = $_POST['selected_classes'];
    $action = '';
    $now = new DateTime();
    
    if (isset($_POST['approved_btn'])) {
        $action = 'approved';
    } elseif (isset($_POST['rejected_btn'])) {
        $action = 'rejected';
    }
    
    $updateChangeClassSql = "UPDATE student_change_class SET status = :status WHERE student_change_class_id = :id";
    $updateChangeClassStmt = $pdo->prepare($updateChangeClassSql);
    
    $getClassIdSql = "SELECT class_id FROM class WHERE class_code = :C_CODE";
    $getClassIdStmt = $pdo->prepare($getClassIdSql);

    $updateClassSql = "UPDATE student_class SET class_id = :new_C_ID, enroll_date = :date_now WHERE student_id = :S_ID AND class_id = :Old_C_ID";
    $updateClassStmt = $pdo->prepare($updateClassSql);

    $updateCurCapacitySql = "UPDATE class SET current_capacity = current_capacity + 1 WHERE class_id = :C_ID AND max_capacity > current_capacity;";
    $updateCurCapacityStmt = $pdo->prepare($updateCurCapacitySql);

    $deductCurCapacitySql = "UPDATE class SET current_capacity = current_capacity - 1 WHERE class_id = :C_ID AND current_capacity > 0;";
    $deductCurCapacityStmt = $pdo->prepare($deductCurCapacitySql);

    foreach ($selectedIds as $combinedValue) {
        list($changeId, $studentId, $new_class_Code, $old_class_Code, $status) = explode('|', $combinedValue);
        
        if($action == 'approved'){
            $getClassIdStmt->execute([':C_CODE'=>$new_class_Code]);
            $new_class_row = $getClassIdStmt->fetch(PDO::FETCH_ASSOC);
            $new_class_id = $new_class_row['class_id'];

            $getClassIdStmt->execute([':C_CODE'=>$old_class_Code]);
            $old_class_row = $getClassIdStmt->fetch(PDO::FETCH_ASSOC);
            $old_class_id = $old_class_row['class_id'];

            $pdo->beginTransaction();
            try {
                $updateCurCapacityStmt->execute([':C_ID'=>$new_class_id]);
                $deductCurCapacityStmt->execute([':C_ID'=>$old_class_id]);
                $updateClassStmt->execute([
                    ':new_C_ID' => $new_class_id,
                    ':date_now'=>$now->format('Y-m-d H:i:s'),
                    ':S_ID' => $studentId,
                    ':Old_C_ID' => $old_class_id
                ]);
                $updateChangeClassStmt->execute([
                    ':status' => $action,
                    ':id' => $changeId
                ]);
                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                $success = 0;
                error_log("Error processing class change: " . $e->getMessage());
            }
        } else if ($action == 'rejected' && $status == 'approved') {
            $rejectionErrors[] = $changeId;
            continue;
        } else if($action == 'rejected' && $status != 'approved'){
            $updateChangeClassStmt->execute([
                ':status' => $action,
                ':id' => $changeId
            ]);
        } else {
            $success = 0;
            break;
        }
    }

    if (!empty($rejectionErrors)) {
        $_SESSION['message'] = "Cannot reject already approved requests for requests: " . implode(", ", $rejectionErrors) . ". \nOther requests were processed successfully.";
    } elseif ($success == 0) {
        $_SESSION['message'] = "Failed to change class. Please Try Again Later!";
    } else {
        $_SESSION['message'] = "Successful to " . ($action == 'approved' ? 'approve' : 'reject') . " class change request(s)!";
    }
    header("Location: manageChangeClass.php");
    exit();
}

if (!empty($keywords)) {

$getChangeClassSql = "SELECT student_change_class_id, student_change_class_reason AS reason, student_original_class AS Ori, student_prefer_class AS Prefer, student_id, status 
                    FROM student_change_class
                    WHERE student_id like :keywords";
$getChangeClassStmt = $pdo->prepare($getChangeClassSql);
$getChangeClassStmt->bindValue(':keywords', '%' . $keywords . '%');
$getChangeClassStmt->execute();
}
else{
$getChangeClassSql = "SELECT student_change_class_id, student_change_class_reason AS reason, student_original_class AS Ori, student_prefer_class AS Prefer, student_id, status FROM student_change_class";
$getChangeClassStmt = $pdo->prepare($getChangeClassSql);
$getChangeClassStmt->execute();
}

$ChangeClassData = $getChangeClassStmt->fetchAll(PDO::FETCH_ASSOC);

$total_items = count($ChangeClassData);

if ($records_per_page === 'ALL') {
    $total_pages = 1;
} else {
    $total_pages = ceil($total_items / $records_per_page);
}

if ($page > $total_pages && $total_pages > 0) {
    $page = $total_pages;
    if ($records_per_page !== 'ALL') {
        $start = ($page - 1) * $records_per_page;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php if (isset($_SESSION['message'])): ?>
    <meta http-equiv="refresh" content="3;url=manageChangeClass.php">
    <?php endif; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headerAdmin.css">
    <link rel="stylesheet" href="../cssfile/changeClass.css">
    <link rel="stylesheet" href="../cssfile/manageClass.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Change Class</title>
</head>
<body>
    <div class="search-container">
        <form action="" method="get">
            <input type="text" name="query" id="searchInput" placeholder="Search Student ID..."  value="<?php echo htmlspecialchars($_SESSION['search_query']); ?>" required>
            <button type="submit" class="search-button" name="search">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        <a href="manageChangeClass.php" class="clear_search"><span class="material-symbols-outlined">close</span></a>
    </div>

    <div class="records-per-page">
        <form method="get" action="">
            <?php if (!empty($_SESSION['search_query'])): ?>
            <input type="hidden" name="query" value="<?php echo htmlspecialchars($_SESSION['search_query']); ?>">
            <?php endif; ?>
            <input type="hidden" name="page" value="1">
            <label for="limit">Records per page:</label>
            <select name="limit" id="limit" onchange="this.form.submit()">
                <option value="5" <?php echo $records_per_page == 5 ? 'selected' : ''; ?>>5</option>
                <option value="10" <?php echo $records_per_page == 10 ? 'selected' : ''; ?>>10</option>
                <option value="15" <?php echo $records_per_page == 15 ? 'selected' : ''; ?>>15</option>
                <option value="20" <?php echo $records_per_page == 20 ? 'selected' : ''; ?>>20</option>
                <option value="ALL" <?php echo $records_per_page === 'ALL' ? 'selected' : ''; ?>>All</option>
            </select>
        </form>
    </div>

    <form action="manageChangeClass.php" method="post">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Request ID</th>
                    <th>Student ID</th>
                    <th>Original Class</th>
                    <th>Preferred Class</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($ChangeClassData)): ?>
                    <?php if ($records_per_page === 'ALL'): ?>
                        <?php foreach ($ChangeClassData as $row): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_classes[]" value="<?= htmlspecialchars($row['student_change_class_id'].'|'.$row['student_id'].'|'.$row['Prefer'].'|'.$row['Ori'].'|'.$row['status'])?>"></td>
                                <td><?php echo htmlspecialchars($row['student_change_class_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['Ori']); ?></td>
                                <td><?php echo htmlspecialchars($row['Prefer']); ?></td>
                                <td class="wrap_text" ><?php echo htmlspecialchars($row['reason']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php for ($i = $start; $i < $start + $records_per_page && $i < count($ChangeClassData); $i++): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_classes[]" value="<?= htmlspecialchars($ChangeClassData[$i]['student_change_class_id'].'|'.$ChangeClassData[$i]['student_id'].'|'.$ChangeClassData[$i]['Prefer'].'|'.$ChangeClassData[$i]['Ori'].'|'.$ChangeClassData[$i]['status'])?>"></td>
                                <td><?php echo htmlspecialchars($ChangeClassData[$i]['student_change_class_id']); ?></td>
                                <td><?php echo htmlspecialchars($ChangeClassData[$i]['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($ChangeClassData[$i]['Ori']); ?></td>
                                <td><?php echo htmlspecialchars($ChangeClassData[$i]['Prefer']); ?></td>
                                <td class="wrap_text" ><?php echo htmlspecialchars($ChangeClassData[$i]['reason']); ?></td>
                                <td><?php echo htmlspecialchars($ChangeClassData[$i]['status']); ?></td>
                            </tr>
                        <?php endfor; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No change class requests.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="action-buttons">
            <button type="submit" name="approved_btn" value="approved" class="approved_btn" onclick="return confirmAction('approve')">Approve</button>
            <button type="submit" name="rejected_btn" value="rejected" class="rejected_btn" onclick="return confirmAction('reject')">Reject</button>
            <button type="button" onclick="printTable()" class="print_button">Print Table</button>
            <button type="button" onclick="saveAsPDF()" class="pdf_button">Save as PDF</button>
        </div>
    </form>

    <?php if ($records_per_page !== 'ALL' && $total_pages > 1): ?>
    <div class="pagination">
        <?php
        $page_params = [
            'query' => $_SESSION['search_query'],
            'limit' => $records_per_page
        ];
        $query_string = http_build_query($page_params);
        
        echo '<a href="?page=1&'.$query_string.'" title="First Page">&laquo;&laquo;</a> ';
        
        if ($page > 1) {
            echo '<a href="?page='.($page - 1).'&'.$query_string.'" title="Previous Page">&laquo;</a> ';
        } else {
            echo '<span class="disabled">&laquo;</span> ';
        }
        
        echo '<span class="current">'.$page.' of '.$total_pages.'</span> ';
        
        if ($page < $total_pages) {
            echo '<a href="?page='.($page + 1).'&'.$query_string.'" title="Next Page">&raquo;</a> ';
        } else {
            echo '<span class="disabled">&raquo;</span> ';
        }
        
        echo '<a href="?page='.$total_pages.'&'.$query_string.'" title="Last Page">&raquo;&raquo;</a>';
        ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['message'])): ?>
        <script>
            alert(<?php echo json_encode($_SESSION['message']); ?>);
        </script>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <script>
    function confirmAction(action) {
        return confirm("Are you sure you want to " + action + " the selected request(s)?");
    }
    
    function printTable() {
        const printContent = document.querySelector('table').outerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h1 style="text-align:center;">Class Change Requests</h1>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
    
    function saveAsPDF() {
        const element = document.querySelector('table');
        const opt = {
            margin: 10,
            filename: 'class_change_requests.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { 
                scale: 2,
                useCORS: true,
                logging: true 
            },
            jsPDF: { 
                unit: 'mm', 
                format: 'a4',
                orientation: 'landscape' 
            }
        };
        
        const originalText = document.querySelector('.pdf_button').textContent;
        document.querySelector('.pdf_button').textContent = 'Generating...';
        document.querySelector('.pdf_button').disabled = true;
        
        html2pdf()
            .set(opt)
            .from(element)
            .save()
            .then(() => {
                document.querySelector('.pdf_button').textContent = originalText;
                document.querySelector('.pdf_button').disabled = false;
            })
            .catch(err => {
                console.error('PDF error:', err);
                alert('PDF generation failed. See console for details.');
                document.querySelector('.pdf_button').textContent = originalText;
                document.querySelector('.pdf_button').disabled = false;
            });
    }
    </script>
</body>
</html>