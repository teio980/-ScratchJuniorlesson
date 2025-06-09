<?php
try {
    include "connect_DB.php";

    $sql_get_total_perosn = "SELECT COUNT(*) FROM student_class";
    $total = $pdo->query($sql_get_total_perosn)->fetchColumn();

    $sql_get_total_perosn_pass = "SELECT COUNT(*) FROM student_class WHERE average_score >= 40";
    $passed = $pdo->query($sql_get_total_perosn_pass)->fetchColumn();

    $pass_rate = ($passed / $total) * 100;

    echo json_encode([
        'pass_rate' => round($pass_rate, 2),
        'total_students' => $total,
        'passed_students' => $passed
    ]);

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>