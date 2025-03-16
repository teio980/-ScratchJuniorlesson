<?php

$DB = "mysql:host=localhost;dbname=scratchjunior";
$DB_username = "root";
$DB_password = "";

try {
    $pdo = new PDO($DB,$DB_username,$DB_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection Failed:" . $e->getMessage();
}

?>