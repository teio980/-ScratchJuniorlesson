<?php
    session_start();
    include '../includes/connect_DB.php';
    include 'header_Admin.php';
    $userID = $_SESSION["user_id"];
    $sql = "SELECT A_Username AS Username, A_Mail AS Mail FROM admin WHERE admin_id LIKE :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $userID );
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $Avatar_directory = "Avatar/";
    $files = scandir($Avatar_directory);
    $fileFound = false;
    foreach ($files as $file) {
        if ($file != "." && $file != "..") { 
            $avatar = pathinfo($file, PATHINFO_FILENAME);
            
            if ($avatar == $userID) {
                $fullPath = $Avatar_directory . DIRECTORY_SEPARATOR . $file; 
                $fileFound = true;
                break; 
            }else{
                $fullPath = 'Avatar/avatar_default.png';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headerAdmin.css">
    <link rel="stylesheet" href="../cssfile/personal_profile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Document</title>
</head>
<body>
    <h1>Personal Profile</h1>
    <form action="../includes/change_admin_Avatar.php" class="avatar_container" method="post" enctype="multipart/form-data">
    <input type="file" name="change_Avatar" id="change_Avatar" accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="this.form.submit()">
    <label for="change_Avatar" style="cursor: pointer;">
        <img src="<?php echo $fullPath; ?>" alt="Avatar" class="avatar">
        <span class="material-symbols-outlined" id="edit_avatar_icon">edit</span>
    </label>
    </form>
    <form action="../includes/change_Username.php" method="post" class="changeUsernameEmail_box">
        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user_id) ?>">
        <div class="UsernameEmail_box">
        <label for="new_Username">Username(6-12 Characters):</label>
        <input type="text" name="new_Username" id="new_Username"  minlength="6" maxlength="12" required value="<?php echo htmlspecialchars($user['Username']) ?>">
        </div>

        <div class="UsernameEmail_box">
        <label for="new_Mail">E-mail:</label>
        <input type="email" name="new_Mail" id="new_Mail" required  value="<?php echo htmlspecialchars($user['Mail']) ?>">
        </div>

        <button type="submit" class="save_btn">Save Changes</button>
    </form>
    <form action="../includes/change_Password.php" method="post" class="changePassword_box">
        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user_id) ?>">

        <div class="password_box">
        <label for="old_Password">Old Password:</label>
        <input type="password" name="old_Password" id="old_Password" required>
        <span class="material-symbols-outlined" id="showOldPassword_icon" onclick="showPassword('old_Password','showOldPassword_icon') ">visibility_off</span>
        </div>

        <div class="password_box">
        <label for="new_Password">New Password:</label>
        <input type="password" name="new_Password" id="new_Password" required>
        <span class="material-symbols-outlined" id="showNewPassword_icon" onclick="showPassword('new_Password','showNewPassword_icon') ">visibility_off</span>
        </div>

        <div class="password_box">
        <label for="new_Password">Confirmed New Password:</label>
        <input type="password" name="confirmed_new_Password" id="confirmed_new_Password" required>
        <span class="material-symbols-outlined" id="showNewConfirmedPassword_icon" onclick="showPassword('confirmed_new_Password','showNewConfirmedPassword_icon') ">visibility_off</span>
        </div>

        <button type="submit" class="save_btn">Save Changes</button>
    </form>
</body>
<script>
    function showPassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (passwordInput && icon) {
        if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = 'visibility';
        } else {
        passwordInput.type = 'password';
        icon.textContent = 'visibility_off';
        }
    }
    }

    document.querySelector('form.changePassword_box').addEventListener('submit', function(e) {
    const newPass = document.getElementById('new_Password').value;
    const confirmPass = document.getElementById('confirmed_new_Password').value;

    if (newPass !== confirmPass) {
        e.preventDefault(); 
        alert('New Password does not match Confirmed Password!');
        return false;
    }

    return true;
    });
</script>
</html>