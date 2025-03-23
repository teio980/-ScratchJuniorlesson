<?php
include 'connect_DB.php';
$password = $_POST["U_Password"];
$token = $_POST["token"];
$token_hash = hash("sha256",$token);

$sql = "SELECT * FROM user WHERE reset_token = ? AND reset_token_expires > NOW()";
$stmt = $pdo->prepare($sql);
$stmt->execute([$token_hash]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Invalid or expired token.";
    exit;
}

if (strlen($password) <= 8 && strlen($password) >= 12) {
    die("Password length must between 8-12 characters");
}

if (!preg_match('/\d/', $password)) {
    die("Password must contain at least one number");
}

if (!preg_match('/[A-Z]/', $password)) {
    die("Password must contain at least one Uppercase");
}

if (!preg_match('/[a-z]/', $password)) {
    die("Password must contain at least one Lowercase");
}

if (!preg_match('/[^a-zA-Z0-9\s]/', $password)) {
    die("Password must contain at least one Symbol");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE user SET U_Password = ?, reset_token = NULL ,  reset_token_expires = NULL WHERE U_ID = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$hashedPassword,$user["U_ID"]]);

echo "Password Successful Updated."
?>