<?php
include 'connect_DB.php';

$token = $_GET["token"];
$token_hash = hash("sha256",$token);

$sql = "SELECT * FROM student WHERE reset_token = ? AND reset_token_expires > NOW()";
$stmt = $pdo->prepare($sql);
$stmt->execute([$token_hash]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="../cssfile/header.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    <h1>Reset Password</h1>
    <form action="process_reset_password.php" method="post">
        <input type="hidden" name="token" value="<?=htmlspecialchars($token)?>">
        <label for="U_Password">Password:</label>
        <input type="password" id="U_Password" name="U_Password" placeholder="Password" required>
        <div>
            <p id="password_condition_length" class="password_condition"><span class="material-symbols-outlined">close</span> Length is between 8-12 Characters</p>
            <p id="password_condition_digit" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one number</p>
            <p id="password_condition_upper" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one Uppercase</p>
            <p id="password_condition_lower" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one Lowercase</p>
            <p id="password_condition_symbol" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one Symbol</p>
        </div>
        <label for="U_Confirmed_Password">Confirmed Password:</label>
        <input type="password" id="U_Confirmed_Password" placeholder="Password" required>
        <button type="submit">Reset</button>
    </form>
    <script src="register.js"></script>
</body>
</html>