<?php 
include '../includes/connect_DB.php';
include 'header_Admin.php';
$keywords = '';
if (isset($_POST["search"]) && isset($_POST["query"]) && !empty($_POST["query"])) {
            $keywords = $_POST['query'];
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
            LEFT JOIN student_class sc ON c.class_id = sc.class_id
            LEFT JOIN student s ON sc.student_id = s.student_id
            WHERE class_code LIKE :keywords;
            ";
            $getClassPerformanceStmt = $pdo->prepare($getClassPerformanceSql);
            $getClassPerformanceStmt->bindValue(':keywords', '%' . $keywords . '%');
            $getClassPerformanceStmt->execute();
            $ClassPerformanceDetail = $getClassPerformanceStmt->fetchAll(PDO::FETCH_ASSOC);
}else{
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
                            LEFT JOIN student_class sc ON c.class_id = sc.class_id
                            LEFT JOIN student s ON sc.student_id = s.student_id;
                            ";
$getClassPerformanceStmt = $pdo->prepare($getClassPerformanceSql);
$getClassPerformanceStmt->execute();
$ClassPerformanceDetail = $getClassPerformanceStmt->fetchAll(PDO::FETCH_ASSOC);
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
        <form action="" method="post">
            <input type="text" name="query" id="searchInput" placeholder="Search..."  value="<?php echo htmlspecialchars($keywords); ?>" required>
            <button type="submit" class="search-button" name="search">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        <a href="viewClassPerformance.php" class="clear_search"><span class="material-symbols-outlined">close</span></a>
    </div>

    <form action="delete_Class.php" method="post">
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
                <?php foreach ($ClassPerformanceDetail as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['class_id']) ?></td>
                        <td><?= htmlspecialchars($row['class_code']) ?></td>
                        <td><?= htmlspecialchars($row['class_name']) ?></td>
                        <td><?= htmlspecialchars($row['teacher_id']) ?></td>
                        <td><?= htmlspecialchars($row['T_Username']) ?></td>
                        <td><a target="_blank" href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= urlencode($row['T_Mail']) ?>"><?= htmlspecialchars($row['T_Mail']) ?></a></td>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                        <td><?= htmlspecialchars($row['S_Username']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
    </form>
</body>
</html>