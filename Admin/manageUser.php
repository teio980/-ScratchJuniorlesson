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
            <span class="close" onclick="closeModal() ">&times;</span>
            <h2>Edit User</h2>
            <form id="editForm" method="post" action="update_users.php">
            <input type="hidden" id="editUserId" name="UserID">

            <label for="U_Username">Username (6-12 Characters):</label>
            <input type="text" id="U_Username" name="U_Username" placeholder="Username" required minlength="6" maxlength="12">

            <label for="U_Email">Email:</label>
            <input type="email" id="U_Email" name="U_Email" placeholder="e.g.: abc123@gmail.com" required>

            <label for="identity">Identity</label>
            <input type="radio" name="identity" id="identity_S" value="student"> Student
            <input type="radio" name="identity" id="identity_T" value="teacher"> Teacher

            <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
</body>
<script src="manageUser.js"></script>
</html>