<?php
session_start();
include 'header_Admin.php';
include '../includes/connect_DB.php';
$identity = $_SESSION['identity'];
$users = [];

if (isset($_GET['query'])) {
    $_SESSION['search_query'] = $_GET['query'];
} elseif (!isset($_SESSION['search_query'])) {
    $_SESSION['search_query'] = '';
}

if (isset($_GET['clear_search'])) {
    $_SESSION['search_query'] = '';
}

$keywords = $_SESSION['search_query'];

if (isset($_GET['limit'])) {
    if ($_GET['limit'] == 'ALL') {
        $records_per_page = 'ALL';
    } else {
        $records_per_page = (int)$_GET['limit'];
    }
} else {
    $records_per_page = 15; 
}

if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
    if ($page < 1) {
        $page = 1;
    }
} else {
    $page = 1;
}

if ($records_per_page === 'ALL') {
    $start = 0;
} else {
    $start = ($page - 1) * $records_per_page;
}

if (!empty($keywords))  {
    if($identity == "superadmin"){
        $sql = "SELECT identity , student_id AS U_ID , S_Username AS U_Username, S_Mail AS U_Mail FROM student WHERE S_Username LIKE :keywords
                UNION ALL
                SELECT identity , teacher_id AS U_ID , T_Username AS U_Username, T_Mail AS U_Mail FROM teacher WHERE T_Username LIKE :keywords
                UNION ALL
                SELECT identity , admin_id AS U_ID , A_Username AS U_Username, A_Mail AS U_Mail FROM admin WHERE A_Username LIKE :keywords AND identity != 'superadmin'
                ORDER BY U_Username ASC"; 
    }else{
       $sql = "SELECT identity , student_id AS U_ID , S_Username AS U_Username, S_Mail AS U_Mail FROM student WHERE S_Username LIKE :keywords
            UNION ALL
            SELECT identity , teacher_id AS U_ID , T_Username AS U_Username, T_Mail AS U_Mail FROM teacher WHERE T_Username LIKE :keywords
            ORDER BY U_Username ASC"; 
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
                SELECT identity , admin_id AS U_ID , A_Username AS U_Username, A_Mail AS U_Mail FROM admin WHERE identity != 'superadmin'
                ORDER BY U_Username ASC"; 
    }else{
       $sql = "SELECT identity , student_id AS U_ID , S_Username AS U_Username, S_Mail AS U_Mail FROM student 
            UNION ALL
            SELECT identity , teacher_id AS U_ID , T_Username AS U_Username, T_Mail AS U_Mail FROM teacher
            ORDER BY U_Username ASC";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll();
}

$total_items = count($users);

if ($records_per_page === 'ALL') {
    $total_pages = 1;
} else {
    $total_pages = ceil($total_items / $records_per_page);
}

if ($page > $total_pages && $total_pages > 0) {
    $page = $total_pages;
    if ($records_per_page !== 'ALL') {
        $start = ($page - 1) * $records_per_page;
    }
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <title>Manage Users</title>
</head>
<body>
    <div class="search-container">
        <form action="" method="get">
            <?php if (isset($_GET['limit'])): ?>
                <input type="hidden" name="limit" value="<?php echo htmlspecialchars($_GET['limit']); ?>">
            <?php endif; ?>
            <input type="text" name="query" id="searchInput" placeholder="Search Student ID..." value="<?php echo htmlspecialchars($_SESSION['search_query']); ?>">
            <button type="submit" class="search-button" name="search">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        <a href="manageUser.php?clear_search=1" class="clear_search"><span class="material-symbols-outlined">close</span></a>
    </div>
    
    <div class="records-per-page">
        <form method="get" action="">
            <?php if (!empty($_SESSION['search_query'])): ?>
            <input type="hidden" name="query" value="<?php echo htmlspecialchars($_SESSION['search_query']); ?>">
            <?php endif; ?>
            <input type="hidden" name="page" value="1">
            <label for="limit">Records per page:</label>
            <select name="limit" id="limit" onchange="this.form.submit()">
                <option value="5" <?php echo $records_per_page == 5 ? 'selected' : ''; ?>>5</option>
                <option value="10" <?php echo $records_per_page == 10 ? 'selected' : ''; ?>>10</option>
                <option value="15" <?php echo $records_per_page == 15 ? 'selected' : ''; ?>>15</option>
                <option value="20" <?php echo $records_per_page == 20 ? 'selected' : ''; ?>>20</option>
                <option value="ALL" <?php echo $records_per_page === 'ALL' ? 'selected' : ''; ?>>All</option>
            </select>
        </form>
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
            <?php if (!empty($users)): ?>
                <?php if ($records_per_page === 'ALL'): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>   
                            <td><input type="checkbox" name="selected_users[]" value="<?php echo $user['U_ID'] . '|' . $user['identity']?>"></td>
                            <td><?php echo htmlspecialchars($user['U_ID']) ?></td>
                            <td><?php echo htmlspecialchars($user['U_Username']) ?></td>
                            <td><?php echo htmlspecialchars($user['U_Mail']) ?></td>
                            <td><?php echo htmlspecialchars($user['identity']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php for ($i = $start; $i < $start + $records_per_page && $i < count($users); $i++): ?>
                        <tr>   
                            <td><input type="checkbox" name="selected_users[]" value="<?php echo $users[$i]['U_ID'] . '|' . $users[$i]['identity']?>"></td>
                            <td><?php echo htmlspecialchars($users[$i]['U_ID']) ?></td>
                            <td><?php echo htmlspecialchars($users[$i]['U_Username']) ?></td>
                            <td><?php echo htmlspecialchars($users[$i]['U_Mail']) ?></td>
                            <td><?php echo htmlspecialchars($users[$i]['identity']) ?></td>
                        </tr>
                    <?php endfor; ?>
                <?php endif; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No User Record.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table> 
        <div class="action-buttons">
            <a href="addUser.php" style="text-decoration: none;">
                <button type="button" class="add_btn">Add</button>
            </a>
            <button type="button" onclick="showEditForm()" class="edit_btn">Edit</button>
            <button type="submit" class="delete_btn" onclick="return confirmDelete()">Delete</button>
            <button onclick="printTable()" class="print_button">Print Table</button>
            <button onclick="saveAsPDF()" class="pdf_button">Save as PDF</button>
        </div>
    </form>

    <?php if ($records_per_page !== 'ALL' && $total_pages > 1): ?>
    <div class="pagination">
        <?php
        $page_params = [
            'query' => $_SESSION['search_query'],
            'limit' => $records_per_page
        ];
        $query_string = http_build_query($page_params);
        
        echo '<a href="?page=1&'.$query_string.'" title="First Page">&laquo;&laquo;</a> ';
        
        if ($page > 1) {
            echo '<a href="?page='.($page - 1).'&'.$query_string.'" title="Previous Page">&laquo;</a> ';
        } else {
            echo '<span class="disabled">&laquo;</span> ';
        }
        
        echo '<span class="current">'.$page.' of '.$total_pages.'</span> ';
        
        if ($page < $total_pages) {
            echo '<a href="?page='.($page + 1).'&'.$query_string.'" title="Next Page">&raquo;</a> ';
        } else {
            echo '<span class="disabled">&raquo;</span> ';
        }
        
        echo '<a href="?page='.$total_pages.'&'.$query_string.'" title="Last Page">&raquo;&raquo;</a>';
        ?>
    </div>
    <?php endif; ?>

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
    
    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete the selected users?");
    }
    
    function printTable() {
        const printContent = document.querySelector('table').outerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h1 style="text-align:center;">User Management Table</h1>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
    
    function saveAsPDF() {
        const element = document.querySelector('table');
        const opt = {
            margin: 10,
            filename: 'user_management.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { 
                scale: 2,
                useCORS: true,
                logging: true 
            },
            jsPDF: { 
                unit: 'mm', 
                format: 'a4',
                orientation: 'landscape' 
            }
        };
        
        const originalText = document.querySelector('.pdf_button').textContent;
        document.querySelector('.pdf_button').textContent = 'Generating...';
        document.querySelector('.pdf_button').disabled = true;
        
        html2pdf()
            .set(opt)
            .from(element)
            .save()
            .then(() => {
                document.querySelector('.pdf_button').textContent = originalText;
                document.querySelector('.pdf_button').disabled = false;
            })
            .catch(err => {
                console.error('PDF error:', err);
                alert('PDF generation failed. See console for details.');
                document.querySelector('.pdf_button').textContent = originalText;
                document.querySelector('.pdf_button').disabled = false;
            });
    }
    </script>
    <script src="manageUser.js"></script>
</body>
</html>