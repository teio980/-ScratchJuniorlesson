<?php
session_start();
include '../resheadAfterLogin.php';
include '../includes/connect_DB.php';

$class = [];
$keywords = '';
if (isset($_POST["search"]) && isset($_POST["query"]) && !empty($_POST["query"])) {
    $keywords = $_POST['query'];
    $class_sql = "SELECT class_id, class_code , class_name ,class_description AS description , max_capacity , current_capacity FROM class WHERE class_name LIKE :keywords";
    $stmt = $pdo->prepare($class_sql);
    $stmt->bindValue(':keywords', '%' . $keywords . '%');
    $stmt->execute();
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = [];

    foreach ($classes as $class) {
        $teacher_sql = "SELECT t.teacher_id, t.teacher_name
                        FROM teacher_class tc
                        JOIN teacher t ON tc.teacher_id = t.teacher_id
                        WHERE tc.class_id = :class_id";
        
        $stmt = $pdo->prepare($teacher_sql);
        $stmt->bindValue(':class_id', $class['class_id']);
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $class['teachers'] = $teachers;
        $result[] = $class;
    }
} else {
    $class_sql = "SELECT class_id, class_code , class_name ,class_description AS description , max_capacity , current_capacity FROM class ";
    $stmt = $pdo->prepare($class_sql);
    $stmt->execute();
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = [];

    foreach ($classes as $class) {
        $teacher_sql = "SELECT t.teacher_id, t.T_Username
                        FROM teacher_class tc
                        JOIN teacher t ON tc.teacher_id = t.teacher_id
                        WHERE tc.class_id = :class_id";
        
        $stmt = $pdo->prepare($teacher_sql);
        $stmt->bindValue(':class_id', $class['class_id']);
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $class['teachers'] = $teachers;
        $result[] = $class;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/reshead.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="../cssfile/manageClass.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Manage Class</title>
</head>
<body>
    <div class="search-container">
        <form action="" method="post">
            <input type="text" name="query" id="searchInput" placeholder="Search..."  value="<?php echo htmlspecialchars($keywords); ?>" required>
            <button type="submit" class="search-button" name="search">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        <a href="manageClass.php" class="clear_search"><span class="material-symbols-outlined">close</span></a>
    </div>

    <form action="delete_Class.php" method="post">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Class Code</th>
                    <th>Class Name</th>
                    <th>Teach By</th>
                    <th>Description</th>
                    <th>Max Capacity</th>
                    <th>Current Capacity</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($result as $class): ?>
                <tr>   
                    <td><input type="checkbox" name="selected_classes[]" value="<?= htmlspecialchars($class['class_id'])?>"></td>
                    <td><?php echo htmlspecialchars($class['class_id']) ?></td>
                    <td><?php echo htmlspecialchars($class['class_code']) ?></td>
                    <td><?php echo htmlspecialchars($class['class_name']) ?></td>
                    <td><?php 
                    $teacherNames = array();
                    foreach ($class['teachers'] as $teacher) {
                        $teacherNames[] = htmlspecialchars($teacher['T_Username']);
                    }
                    echo implode(', ', $teacherNames);
                    ?></td>
                    <td><?php echo htmlspecialchars($class['description']) ?></td>
                    <td><?php echo htmlspecialchars($class['max_capacity']) ?></td>
                    <td><?php echo htmlspecialchars($class['current_capacity']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table> 
        <button type="button" onclick="showAddForm()" class="add_btn">Add</button>
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
                <form id="editForm" method="post" action="update_class.php">
                    <input type="hidden" id="editClassId" name="ClassID">
                
                <div class="form-group">
                    <label for="Class_code">Class Code:</label>
                    <input type="text" id="Class_code" name="Class_code" required>
                </div>
                
                <div class="form-group">
                    <label for="Class_name">Class Name:</label>
                    <input type="text" id="Class_name" name="Class_name" required>
                </div>

                <div class="form-group">
                    <label for="Teacher_name">Teach By:</label>
                    <select id="Teacher_name" name="Teacher_name" class="select2-edit" required>
                    <option value="">-- Select Teacher --</option>
                    <?php
                    $stmt = $pdo->query("SELECT T_Username FROM teacher ORDER BY T_Username ASC");
                    while ($teacher = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$teacher['T_Username']}'>{$teacher['T_Username']}</option>";
                    }
                    ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Class_description">Description:</label>
                    <input type="textarea" id="Class_description" name="Class_description" required>
                </div>
                
                <div class="form-group">
                    <label for="max_capacity">Max Capacity:</label>
                    <input type="number" id="max_capacity" name="max_capacity" required>
                </div>

                <button type="submit" class="save_btn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <div id="addClassModal" class="addClassModal">
        <div class="modalContent">
            <div class="modalHeader">
                <h2>Add Class</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="addFormContent">
                <form id="addForm" method="post" action="add_class.php">
                
                <div class="form-group">
                    <label for="Class_code">Class Code:</label>
                    <input type="text" id="Class_code" name="Class_code" required>
                    <p>Class Code Format:Three UPPERCASE Follow By Four DIGIT <br> Example: ABC1234</p>
                </div>
                
                <div class="form-group">
                    <label for="Class_name">Class Name:</label>
                    <input type="text" id="Class_name" name="Class_name" required>
                </div>

                <div class="form-group">
                <label for="Teacher_name">Teach By:</label>
                <select id="Teacher_name" name="Teacher_name" class="select2-add" required>
                    <option value="">-- Select Teacher --</option>
                    <?php
                    $stmt = $pdo->query("SELECT T_Username FROM teacher ORDER BY T_Username ASC");
                    while ($teacher = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$teacher['T_Username']}'>{$teacher['T_Username']}</option>";
                    }
                    ?>
                </select>
                </div>

                <div class="form-group">
                    <label for="Class_description">Description:</label>
                    <input type="textarea" id="Class_description" name="Class_description" required>
                </div>
                
                <div class="form-group">
                    <label for="max_capacity">Max Capacity:</label>
                    <input type="number" id="max_capacity" name="max_capacity"  min="1"  required>
                </div>

                <button type="submit" class="save_btn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="manageClass.js"></script>
</html>