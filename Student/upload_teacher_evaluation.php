<?php
include '../includes/connect_DB.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $Q1 = $_POST["q1"];
        $Q2 = $_POST["q2"];
        $Q3 = $_POST["q3"];
        $Q4 = $_POST["q4"];
        $Q5 = $_POST["q5"];
        $Q6 = $_POST["q6"];
        $total = $Q1 + $Q2 + $Q3 + $Q4 + $Q5;
        $insertSql = "INSERT INTO evaluation(evaluation_id, question_1, question_2, question_3, question_4, question_5, question_6, total_marks) VALUES(?,?,?,?,?,?,?,?)";
        $insertStmt = $pdo->prepare($insertSql);

        $sql_TE_checkQty = "SELECT COUNT(*) FROM evaluation";
        $stmt_TE_checkQty = $pdo->prepare($sql_TE_checkQty);
        $stmt_TE_checkQty->execute();
        $TE_Qty = $stmt_TE_checkQty->fetchColumn();
        $TE_id = 'TE'.str_pad($TE_Qty + 1, 6, '0', STR_PAD_LEFT);

        $insertStmt->bindParam(1, $TE_id);
        $insertStmt->bindParam(2, $Q1);
        $insertStmt->bindParam(3, $Q2);
        $insertStmt->bindParam(4, $Q3);
        $insertStmt->bindParam(5, $Q4);
        $insertStmt->bindParam(6, $Q5);
        $insertStmt->bindParam(7, $Q6);
        $insertStmt->bindParam(8, $total);

        if($insertStmt->execute()){
            echo "<script>
            alert('Your Response Successful Submitted!');
            window.location.href = 'teacher_evaluation.php';
            </script>";
            exit();
        }else{
            echo "<script>
            alert('Your Response Failed to Submit!');
            window.location.href = 'teacher_evaluation.php';
            </script>";
            exit();
        }
    }

?>