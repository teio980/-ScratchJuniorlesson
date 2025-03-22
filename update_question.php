<?php
session_start(); 

if (isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] += 1;
}

echo json_encode(['status' => 'success']);
?>