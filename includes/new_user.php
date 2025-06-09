<?php
try {
    include "connect_DB.php";

    $sql = "
        SELECT 
            YEAR(S_Register_Time) AS year, 
            MONTH(S_Register_Time) AS month, 
            COUNT(*) AS user_count 
        FROM student 
        GROUP BY year, month
        ORDER BY year, month;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($result as $row) {
        $month_label = $row['year'] . '-' . str_pad($row['month'], 2, '0', STR_PAD_LEFT);
        $data[] = [
            'month' => $month_label,
            'count' => (int)$row['user_count']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
