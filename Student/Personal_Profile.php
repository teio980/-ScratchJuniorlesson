<?php
    session_start();
    include '../includes/connect_DB.php';
    include '../resheadAfterLogin.php';
    $userID = $_SESSION["user_id"];
    $sql = "SELECT S_Username AS Username, S_Mail AS Mail FROM student WHERE student_id LIKE :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $userID );
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Profile</title>
</head>
<body>
    <form action="../includes/change_Username.php" method="post">
        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($userID) ?>">
        <label for="new_Username">Username:</label>
        <input type="text" name="new_Username" id="new_Username" value="<?php echo htmlspecialchars($user['Username']) ?>">

        <label for="new_Mail">E-mail:</label>
        <input type="email" name="new_Mail" id="new_Mail" value="<?php echo htmlspecialchars($user['Mail']) ?>">

        <button type="submit" class="save_btn">Save Changes</button>
    </form>
    <form action="../includes/change_Password.php" method="post">
        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($userID) ?>">
        <label for="old_Password">Old Password:</label>
        <input type="password" name="old_Password" id="old_Password">

        <label for="new_Password">New Password:</label>
        <input type="password" name="new_Password" id="new_Password">

        <label for="new_Password">Confirmed New Password:</label>
        <input type="password" name="confirmed_new_Password" id="confirmed_new_Password">

        <button type="submit" class="save_btn">Save Changes</button>
    </form>
</body>
</html>