<?php
session_start();
include '../resheadAfterLogin.php';
include '../includes/connect_DB.php';
$Sql = "SELECT * FROM user WHERE identity != 'admin'";
$Stmt = $pdo->prepare($Sql);
$Stmt -> execute();
$users = $Stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/reshead.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="../cssfile/manageUser.css">
    <title>Manage Teacher</title>
</head>
<body>
    <form method="post" action="delete_users.php" name="ManageMainForm" id="ManageMainForm">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>E-mail</th>
                    <th>Identity</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>   
                    <td><input type="checkbox" name="selected_ids[]" value="<?php echo $user['U_ID'] ?>"></td>
                    <td><?php echo htmlspecialchars($user['U_ID']) ?></td>
                    <td><?php echo htmlspecialchars($user['U_Username']) ?></td>
                    <td><?php echo htmlspecialchars($user['U_Password']) ?></td>
                    <td><?php echo htmlspecialchars($user['U_Mail']) ?></td>
                    <td><?php echo htmlspecialchars($user['identity']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" onclick="showEditForm()">Edit</button>
        <button type="submit" >Delete</button>
    </form>

    <div id="editFormModal" class="editFormModal">
        <div class="editFormContent">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit User</h2>
            <form id="editForm" method="post" action="update_users.php">
            <input type="hidden" id="editUserId" name="UserID">

            <label for="U_Username">Username (6-12 Characters):</label>
            <input type="text" id="U_Username" name="U_Username" placeholder="Username" required minlength="6" maxlength="12">
            <p id="errMessage_Username" class="errMessage">Username is required</p>

            <label for="U_Email">Email:</label>
            <input type="email" id="U_Email" name="U_Email" placeholder="e.g.: abc123@gmail.com" required>
            <p id="errMessage_Email" class="errMessage">Email is required</p>

            <label for="U_Password">Password:</label>
            <div class="password-box">
                <input type="password" id="U_Password" name="U_Password" placeholder="Password" required>
                <span class="material-symbols-outlined" id="showPassword_icon" onclick="showPassword() ">visibility_off</span>
            </div>
            <div>
                <p id="password_condition_length" class="password_condition"><span class="material-symbols-outlined">close</span> Length is between 8-12 Characters</p>
                <p id="password_condition_digit" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one number</p>
                <p id="password_condition_upper" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one Uppercase</p>
                <p id="password_condition_lower" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one Lowercase</p>
                <p id="password_condition_symbol" class="password_condition"><span class="material-symbols-outlined">close</span> Contain at least one Symbol</p>
            </div>
            <p id="errMessage_Password" class="errMessage">Password is required</p>

            <label for="U_Confirmed_Password">Confirmed Password:</label>
            <div class="password-box">
                <input type="password" id="U_Confirmed_Password" placeholder="Password" required>
                <span class="material-symbols-outlined" id="showConfirmedPassword_icon" onclick="showConfirmedPassword() ">visibility_off</span>
            </div>
            <p id="errMessage_Confirmed_Password" class="errMessage">Confirmed Password is required</p>

            <label for="identity">Identity</label>
            <input type="radio" name="identity" id="identity_S" value="student">
            <input type="radio" name="identity" id="identity_T" value="teacher">

            <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
</body>
<script src="manageUser.js"></script>
<script src="../register.js"></script>
</html>