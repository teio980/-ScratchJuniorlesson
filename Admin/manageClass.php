<?php
session_start();
include 'header_Admin.php';
include '../includes/connect_DB.php';

if (isset($_GET['query'])) {
    $_SESSION['search_query'] = $_GET['query'];
} elseif (!isset($_SESSION['search_query'])) {
    $_SESSION['search_query'] = '';
}

if (isset($_GET['clear_search'])) {
    $_SESSION['search_query'] = '';
}

$keywords = $_SESSION['search_query'];

// Pagination logic
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

// Search and fetch data
if (!empty($keywords)) {
    $class_sql = "SELECT class_id, class_code , class_name ,class_description AS description , max_capacity , current_capacity FROM class WHERE class_name LIKE :keywords";
    $stmt = $pdo->prepare($class_sql);
    $stmt->bindValue(':keywords', '%' . $keywords . '%');
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

// Calculate pagination
$total_items = count($result);

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
    <link rel="stylesheet" href="../cssfile/manageClass.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <title>Manage Class</title>
</head>
<body>
    <div class="search-container">
        <form action="" method="get">
            <?php if (isset($_GET['limit'])): ?>
                <input type="hidden" name="limit" value="<?php echo htmlspecialchars($_GET['limit']); ?>">
            <?php endif; ?>
            <input type="text" name="query" id="searchInput" placeholder="Search Class Name..."  value="<?php echo htmlspecialchars($_SESSION['search_query']); ?>" required>
            <button type="submit" class="search-button" name="search">
                <span class="material-symbols-outlined">search</span>
            </button>
        </form>
        <a href="manageClass.php?clear_search=1" class="clear_search"><span class="material-symbols-outlined">close</span></a>
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
            <tbody><?php if (!empty($result)): ?>
                <?php 
                if ($records_per_page === 'ALL') {
                    foreach ($result as $class): ?>
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
                    <?php endforeach;
                } else {
                    for ($i = $start; $i < $start + $records_per_page && $i < count($result); $i++): ?>
                        <tr>   
                            <td><input type="checkbox" name="selected_classes[]" value="<?= htmlspecialchars($result[$i]['class_id'])?>"></td>
                            <td><?php echo htmlspecialchars($result[$i]['class_id']) ?></td>
                            <td><?php echo htmlspecialchars($result[$i]['class_code']) ?></td>
                            <td><?php echo htmlspecialchars($result[$i]['class_name']) ?></td>
                            <td><?php 
                            $teacherNames = array();
                            foreach ($result[$i]['teachers'] as $teacher) {
                                $teacherNames[] = htmlspecialchars($teacher['T_Username']);
                            }
                            echo implode(', ', $teacherNames);
                            ?></td>
                            <td><?php echo htmlspecialchars($result[$i]['description']) ?></td>
                            <td><?php echo htmlspecialchars($result[$i]['max_capacity']) ?></td>
                            <td><?php echo htmlspecialchars($result[$i]['current_capacity']) ?></td>
                        </tr>
                    <?php endfor;
                } ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No Class Record.</td>
                </tr>
            <?php endif; ?>
            
            </tbody>
        </table> 
        <button type="button" onclick="showAddForm()" class="add_btn">Add</button>
        <button type="button" onclick="showEditForm()" class="edit_btn">Edit</button>
        <button type="submit" class="delete_btn" onclick="return confirmDelete()">Delete</button>
        <button type="button" onclick="printTable()" class="print_button">Print Table</button>
        <button type="button" onclick="saveAsPDF()" class="pdf_button">Save as PDF</button>
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
<script>
function printTable() {
    const printContent = document.querySelector('table').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align:center;">Manage Class Table</h1>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

function saveAsPDF() {
    const element = document.querySelector('table');
    const opt = {
        margin: 10,
        filename: 'manage_class.pdf',
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

function confirmDelete() {
    return confirm('Are you sure you want to delete the selected items?');
}
</script>
</html>