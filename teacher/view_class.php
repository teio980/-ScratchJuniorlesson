<?php
session_start();
include '../phpfile/connect.php';

$teacher_id = $_SESSION['user_id'];

$teacherClasssql = "SELECT class_id FROM teacher_class WHERE teacher_id = '$teacher_id'"; 
$Classid = mysqli_query($connect, $teacherClasssql);

$classList = [];

if ($Classid && mysqli_num_rows($Classid) > 0) {
    while ($row = mysqli_fetch_assoc($Classid)) {
        $class = $row['class_id'];

        $sqlclass = "SELECT class_code, class_name, current_capacity FROM class WHERE class_id = ?";
        $stmt = $connect->prepare($sqlclass);
        $stmt->bind_param("s", $class); 
        $stmt->execute();
        $ClassResult = $stmt->get_result();

        if ($classInfo = $ClassResult->fetch_assoc()) {
            $classList[] = $classInfo;
        }

        $stmt->close(); 
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <link rel="stylesheet" href="../cssfile/view_ssub.css">
    <title>Student Submissions</title>
    <script src="../javascriptfile/download_all.js"></script>
    <script>
       
    </script>
</head>
<body>
    <h2>View Class</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Class CODE</th>
                <th>Class Name</th>
                <th>Student Number</th>
            </tr>
        </thead>
        <tbody>     
            <?php 
                if (!empty($classList)) {
                    foreach ($classList as $class) { 
            ?>
                <tr>
                    <td>
                        <a href="view_onceclass.php?class_id=<?php echo $class['class_code']; ?>">
                            <?php echo $class['class_code']; ?>
                        </a>
                    </td>
                    <td><?php echo $class['class_name']; ?></td>
                    <td><?php echo $class['current_capacity']; ?></td>
                </tr>
            <?php 
                    }
                } else {
                    echo "<tr><td colspan='3'>No classes found.</td></tr>";
                } 
            ?>

        </tbody>
    </table>

    <button onclick="location.href='Main_page.php'">Back to Dashboard</button>

</body>
</html>
