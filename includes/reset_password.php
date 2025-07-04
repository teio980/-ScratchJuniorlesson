<?php
include 'connect_DB.php';

$token = $_GET["token"];
$identity = $_GET['identity'] ?? '';
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
include '../reshead.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../cssfile/reshead.css">
    <link rel="stylesheet" href="../cssfile/reset_password.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    <div class="main">
        <div class="form">
            <h2>Reset Password</h2>
            <form action="process_reset_password.php" method="post" id="resetPassword_form">
            <input type="hidden" name="token" value="<?=htmlspecialchars($token)?>">
            <input type="hidden" name="identity" value="<?=htmlspecialchars($identity)?>">

            <p for="U_Password" class="textrp">Password:</p>
            <div class="password-box">
                <input type="password" id="U_Password" name="U_Password" placeholder="Password" required>
                <span class="material-symbols-outlined" id="showPassword_icon" onclick="showPassword()">visibility_off</span>
            </div>

            <div class="conditionrp">
                <p id="password_condition_length" class="password_condition"><span class="material-symbols-outlined">close</span> Length is between 8–12 Characters</p>
                <p id="password_condition_digit" class="password_condition"><span class="material-symbols-outlined">close</span> At least one number</p>
                <p id="password_condition_upper" class="password_condition"><span class="material-symbols-outlined">close</span> At least one Uppercase</p>
                <p id="password_condition_lower" class="password_condition"><span class="material-symbols-outlined">close</span> At least one Lowercase</p>
                <p id="password_condition_symbol" class="password_condition"><span class="material-symbols-outlined">close</span> At least one Symbol</p>
            </div>

            <p for="U_Confirmed_Password" class="textrp">Confirmed Password:</p>
            <div class="password-box">
                <input type="password" id="U_Confirmed_Password" placeholder="Password" required>
                <span class="material-symbols-outlined" id="showConfirmedPassword_icon" onclick="showConfirmedPassword()">visibility_off</span>
            </div>

            <button type="submit">Reset</button>
            </form>
        </div>
    </div>
    <script src="../reset_password.js"></script>
</body>
</html>