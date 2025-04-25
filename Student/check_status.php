<?php
header('Content-Type: application/json');

require_once '../includes/connect_DB.php'; 

try {
    $stmt = $pdo->query("SELECT evaluation_expires_date FROM evaluation WHERE evaluation_expires_date > NOW() LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => (int)$result['status'] 
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 0]);
}