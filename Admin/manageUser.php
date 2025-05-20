<?php
session_start();
include 'header_Admin.php';
include '../includes/connect_DB.php';
$identity = $_SESSION['identity'];
$users = [];
$keywords = '';
if (isset($_POST["search"]) && isset($_POST["query"]) && !empty($_POST["query"])) {
    $keywords = $_POST['query'];
    if($identity == "superadmin"){
        $sql = "SELECT identity , student_id AS U_ID , S_Username AS U_Username, S_Mail AS U_Mail FROM student WHERE S_Username LIKE :keywords
                UNION ALL
                SELECT identity , teacher_id AS U_ID , T_Username AS U_Username, T_Mail AS U_Mail FROM teacher WHERE T_Username LIKE :keywords
                UNION ALL
                SELECT identity , admin_id AS U_ID , A_Username AS U_Username, A_Mail AS U_Mail FROM admin WHERE A_Username LIKE :keywords AND identity != 'superadmin'"; 
    }else{
       $sql = "SELECT identity , student_id AS U_ID , S_Username AS U_Username, S_Mail AS U_Mail FROM student WHERE S_Username LIKE :keywords
            UNION ALL
            SELECT identity , teacher_id AS U_ID , T_Username AS U_Username, T_Mail AS U_Mail FROM teacher WHERE T_Username LIKE :keywords"; 
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':keywords', '%' . $keywords . '%');
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    if($identity == "superadmin"){
        $sql = "SELECT identity , student_id AS U_ID , S_Username AS U_Username, S_Mail AS U_Mail FROM student
                UNION ALL
                SELECT identity , teacher_id AS U_ID , T_Username AS U_Username, T_Mail AS U_Mail FROM teacher
                UNION ALL
                SELECT identity , admin_id AS U_ID , A_Username AS U_Username, A_Mail AS U_Mail FROM admin WHERE identity != 'superadmin'"; 
    }else{
       $sql = "SELECT identity , student_id AS U_ID , S_Username AS U_Username, S_Mail AS U_Mail FROM student 
            UNION ALL
            SELECT identity , teacher_id AS U_ID , T_Username AS U_Username, T_Mail AS U_Mail FROM teacher";
    }
    
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
    <link rel="stylesheet" href="../cssfile/headerAdmin.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="../cssfile/manageUser.css">
    <title>Manage Teacher</title>
</head>
<body>
    <div class="search-container">
        <form action="" method="post">
            <input type="text" name="query" id="searchInput" placeholder="Search Username..."  value="<?php echo htmlspecialchars($keywords); ?>" required>
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
                    <td><input type="checkbox" name="selected_users[]" value="<?php echo $user['U_ID'] . '|' . $user['identity']?>"></td>
                    <td><?php echo htmlspecialchars($user['U_ID']) ?></td>
                    <td><?php echo htmlspecialchars($user['U_Username']) ?></td>
                    <td><?php echo htmlspecialchars($user['U_Mail']) ?></td>
                    <td><?php echo htmlspecialchars($user['identity']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table> 
        <a href="addUser.php" style="text-decoration: none;">
        <button type="button"class="add_btn">Add</button>
        </a>
        <button type="button" onclick="showEditForm()" class="edit_btn">Edit</button>
        <button type="submit" class="delete_btn" onclick="return confirmDelete()">Delete</button>
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
                    <input type="hidden" id="editIdentity" name="Identity">

                <div class="form-group">
                    <label for="U_Username">Username (6-12 Characters):</label>
                    <input type="text" id="U_Username" name="U_Username" placeholder="Username" required minlength="6" maxlength="12">
                </div>

                <div class="form-group">
                    <label for="U_Email">Email:</label>
                    <input type="email" id="U_Email" name="U_Email" placeholder="e.g.: abc123@gmail.com" required>
                </div>

                <button type="submit" class="save_btn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="manageUser.js"></script>
</html>