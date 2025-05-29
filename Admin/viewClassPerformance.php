<?php 
include '../includes/connect_DB.php';
include 'header_Admin.php';
$keywords = '';
$limit = 15; 

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start = ($page - 1) * $limit;

if (isset($_GET["query"]) && !empty($_GET["query"])) {
    $keywords = $_GET['query'];
    $getClassPerformanceSql = "SELECT 
                                    c.class_id,
                                    c.class_code,
                                    c.class_name,
                                    t.teacher_id,
                                    t.T_Username,
                                    t.T_Mail,
                                    s.student_id,
                                    s.S_Username
    FROM class c
    LEFT JOIN teacher_class tc ON c.class_id = tc.class_id
    LEFT JOIN teacher t ON tc.teacher_id = t.teacher_id
    INNER JOIN student_class sc ON c.class_id = sc.class_id
    INNER JOIN student s ON sc.student_id = s.student_id
    WHERE class_code LIKE :keywords 
    ";
    $getClassPerformanceStmt = $pdo->prepare($getClassPerformanceSql);
    $getClassPerformanceStmt->bindValue(':keywords', '%' . $keywords . '%');
    $getClassPerformanceStmt->execute();
    $ClassPerformanceDetail = $getClassPerformanceStmt->fetchAll(PDO::FETCH_ASSOC);
    $total_items = count($ClassPerformanceDetail);
    $total_pages = ceil($total_items / $limit);
} else {
    $getClassPerformanceSql = "SELECT 
                                    c.class_id,
                                    c.class_code,
                                    c.class_name,
                                    t.teacher_id,
                                    t.T_Username,
                                    t.T_Mail,
                                    s.student_id,
                                    s.S_Username
                                FROM class c
                                LEFT JOIN teacher_class tc ON c.class_id = tc.class_id
                                LEFT JOIN teacher t ON tc.teacher_id = t.teacher_id
                                INNER JOIN student_class sc ON c.class_id = sc.class_id
                                INNER JOIN student s ON sc.student_id = s.student_id
                                ";
    $getClassPerformanceStmt = $pdo->prepare($getClassPerformanceSql);
    $getClassPerformanceStmt->execute();
    $ClassPerformanceDetail = $getClassPerformanceStmt->fetchAll(PDO::FETCH_ASSOC);
    $total_items = count($ClassPerformanceDetail);
    $total_pages = ceil($total_items / $limit);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headerAdmin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="../cssfile/manageClass.css">
    <title>Class Performance</title>
</head>
<body>
    <div class="search-container">
        <form action="" method="get">
            <input type="text" name="query" id="searchInput" placeholder="Search Class Code..."  value="<?php echo htmlspecialchars($keywords); ?>" required>
            <button type="submit" class="search-button">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        <a href="viewClassPerformance.php" class="clear_search"><span class="material-symbols-outlined">close</span></a>
    </div>

    <form>
        <table>
            <thead>
                <tr>
                    <th>Class ID</th>
                    <th>Class Code</th>
                    <th>Class Name</th>
                    <th>Teacher ID</th>
                    <th>Teacher Username</th>
                    <th>Teacher Email</th>
                    <th>Student ID</th>
                    <th>Student Username</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                for ($i = $start; $i < $start + $limit && $i < count($ClassPerformanceDetail); $i++): ?>
                    <tr>
                        <td><?= htmlspecialchars($ClassPerformanceDetail[$i]['class_id']) ?></td>
                        <td><?= htmlspecialchars($ClassPerformanceDetail[$i]['class_code']) ?></td>
                        <td><?= htmlspecialchars($ClassPerformanceDetail[$i]['class_name']) ?></td>
                        <td><?= htmlspecialchars($ClassPerformanceDetail[$i]['teacher_id']) ?></td>
                        <td><?= htmlspecialchars($ClassPerformanceDetail[$i]['T_Username']) ?></td>
                        <td>
                            <a target="_blank" href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= urlencode($ClassPerformanceDetail[$i]['T_Mail']) ?>">
                                <?= htmlspecialchars($ClassPerformanceDetail[$i]['T_Mail']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($ClassPerformanceDetail[$i]['student_id']) ?></td>
                        <td><?= htmlspecialchars($ClassPerformanceDetail[$i]['S_Username']) ?></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <button onclick="printTable()" class="print-button">Print Table</button>
    </form>
    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php 
            $query_params = '';
            if (!empty($keywords)) {
                $query_params = '&query=' . urlencode($keywords);
            }
            ?>
            
            <?php if ($page > 1): ?>
                <a href="?page=1<?= $query_params ?>">&laquo;&laquo;</a>
                <a href="?page=<?= $page - 1 ?><?= $query_params ?>">&laquo;</a>
            <?php else: ?>
                <span class="disabled">&laquo;&laquo;</span>
                <span class="disabled">&laquo;</span>
            <?php endif; ?>

            <?php
            $range = 2; 
            $start_page = max(1, $page - $range);
            $end_page = min($total_pages, $page + $range);

            if ($start_page > 1) {
                echo '<a href="?page=1' . $query_params . '">1</a>';
                if ($start_page > 2) echo '<span>...</span>';
            }

            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $page) {
                    echo '<span class="current">' . $i . '</span>';
                } else {
                    echo '<a href="?page=' . $i . $query_params . '">' . $i . '</a>';
                }
            }

            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) echo '<span>...</span>';
                echo '<a href="?page=' . $total_pages . $query_params . '">' . $total_pages . '</a>';
            }
            ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?><?= $query_params ?>">&raquo;</a>
                <a href="?page=<?= $total_pages ?><?= $query_params ?>">&raquo;&raquo;</a>
            <?php else: ?>
                <span class="disabled">&raquo;</span>
                <span class="disabled">&raquo;&raquo;</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
<script>
function printTable() {
    const printContent = document.querySelector('table').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align:center;">Class Performance Table</h1>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>

</html>