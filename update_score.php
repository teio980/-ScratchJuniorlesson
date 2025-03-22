<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$isCorrect = $data['isCorrect'];

if ($isCorrect) {
    $_SESSION['score'] += 1;
}

if (isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] += 1;
}

echo json_encode(['status' => 'success']);
?>