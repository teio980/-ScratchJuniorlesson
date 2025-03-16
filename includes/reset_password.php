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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../cssfile/header.css">
</head>
<body>
    <div class="main_header">
        <div class="leftsection">
            <div class="logo"><img src="" alt=""></div>
            <div class="menu_choice">
                <a href="landingpage.html">Home</a>
                <a href="AboutUs.html">About Us</a>
                <a href="Course.html">Course</a>
                <a href="contactUs.html">Contact Us</a>
            </div>
        </div>
        <div class="rightsection">
            <a href="login.php">Sign In</a>
            <a href="register.html">Create Account</a>
            <a href="frequencyAskQuestions.html">FAQ</a>
        </div>
    </div>
    <h1>Reset Password</h1>
    <form action="process_reset_password.php" method="post">
        <input type="hidden" name="token" value="<?=htmlspecialchars($token)?>">
        <label for="U_Password">Password:</label>
        <input type="password" id="U_Password" name="U_Password" placeholder="Password" required>
        <label for="U_Confirmed_Password">Confirmed Password:</label>
        <input type="password" id="U_Confirmed_Password" placeholder="Password" required>
        <button type="submit">Reset</button>
    </form>
</body>
</html>