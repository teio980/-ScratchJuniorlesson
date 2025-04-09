<?php
session_start();
include '../resheadAfterLogin.php';
include '../includes/connect_DB.php';

$users = [];
$keywords = '';
if (isset($_POST["search"]) && isset($_POST["query"]) && !empty($_POST["query"])) {
    $keywords = $_POST['query'];
    $sql = "SELECT * FROM user WHERE identity != 'admin' AND U_Username LIKE :keywords";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':keywords', '%' . $keywords . '%');
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql = "SELECT * FROM user WHERE identity != 'admin'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll();
}
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
    <div class="search-container">
        <form action="" method="post">
            <input type="text" name="query" id="searchInput" placeholder="Search..."  value="<?php echo htmlspecialchars($keywords); ?>" required>
            <button type="submit" class="search-button" name="search">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        <a href="manageUser.php" class="clear_search"><span class="material-symbols-outlined">close</span></a>
    </div>
    
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
        <button type="button" onclick="showEditForm()" class="edit_btn">Edit</button>
        <button type="submit" class="delete_btn">Delete</button>
    </form>

    <div id="editFormModal" class="editFormModal">
        <div class="modalContent">
            <div class="modalHeader">
                <h2>Edit User</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="editFormContent">
                <form id="editForm" method="post" action="update_users.php">
                    <input type="hidden" id="editUserId" name="UserID">

                <div class="form-group">
                    <label for="U_Username">Username (6-12 Characters):</label>
                    <input type="text" id="U_Username" name="U_Username" placeholder="Username" required minlength="6" maxlength="12">
                </div>

                <div class="form-group">
                    <label for="U_Email">Email:</label>
                    <input type="email" id="U_Email" name="U_Email" placeholder="e.g.: abc123@gmail.com" required>
                </div>

                <div class="form-group">
                    <label>Identity</label>
                    <div class="radio-group">
                        <label>
                            Student
                            <input type="radio" name="identity" id="identity_S" value="student">
                        </label>
                        <label>
                            Teacher
                            <input type="radio" name="identity" id="identity_T" value="teacher">
                        </label>
                    </div>
                </div>

                <button type="submit" class="save_btn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="manageUser.js"></script>
</html>