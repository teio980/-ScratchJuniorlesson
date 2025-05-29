<?php
include 'connect_DB.php';
$password = $_POST["U_Password"];
$token = $_POST["token"];
$identity = $_POST["identity"];
$token_hash = hash("sha256",$token);

if($identity == 'student') {
    $sql = "SELECT * FROM student 
            WHERE reset_token = ? AND reset_token_expires > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token_hash]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
} elseif($identity == 'teacher') {
    $sql = "SELECT * FROM teacher 
            WHERE reset_token = ? AND reset_token_expires > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token_hash]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
} elseif($identity == 'admin' || $identity == 'superadmin') {
    $sql = "SELECT * FROM admin 
            WHERE reset_token = ? AND reset_token_expires > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token_hash]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
} else {
    die("Invalid user type");
}

if (!$user) {
    echo "Invalid or expired token.";
    exit;
}

if (strlen($password) < 8 || strlen($password) > 12) {
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

if($identity == 'student') {
    $sql = "UPDATE student SET 
            S_Password = ?, 
            reset_token = NULL, 
            reset_token_expires = NULL 
            WHERE student_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hashedPassword, $user['student_id']]);
    
} elseif($identity == 'teacher') {
    $sql = "UPDATE teacher SET 
            T_Password = ?, 
            reset_token = NULL, 
            reset_token_expires = NULL 
            WHERE teacher_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hashedPassword, $user['teacher_id']]);
    
} elseif($identity == 'admin' || $identity == 'superadmin') {
    $sql = "UPDATE admin SET 
            A_Password = ?, 
            reset_token = NULL, 
            reset_token_expires = NULL 
            WHERE admin_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hashedPassword, $user['admin_id']]);
    
} else {
    die("Invalid user type");
}

echo "Password Successful Updated."
?>