<?php
session_start();
include 'header_Admin.php';
include '../includes/connect_DB.php';
$dataset = [];

if (isset($_GET['query'])) {
    $_SESSION['search_query'] = $_GET['query'];
} elseif (!isset($_SESSION['search_query'])) {
    $_SESSION['search_query'] = '';
}

if (isset($_GET['clear_search'])) {
    $_SESSION['search_query'] = '';
}

$keywords = $_SESSION['search_query'];

// Pagination settings
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

if (!empty($keywords)) {
    
    $sql = "SELECT * FROM content_comments WHERE sender_id like :keywords";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':keywords', '%' . $keywords . '%');
    $stmt->execute();
}
else {
    $sql = "SELECT * FROM content_comments"; 
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$dataset = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_items = count($dataset);

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
    <link rel="stylesheet" href="../cssfile/manageClass.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <title>Teacher Comments</title>
</head>
<body>
    <div class="search-container">
        <form action="" method="get">
            <?php if (isset($_GET['limit'])): ?>
                <input type="hidden" name="limit" value="<?php echo htmlspecialchars($_GET['limit']); ?>">
            <?php endif; ?>
            <input type="text" name="query" id="searchInput" placeholder="Search Teacher ID..."  value="<?php echo htmlspecialchars($_SESSION['search_query']); ?>" required>
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
    
    <form method="post" action="delete_teacher_comments.php" name="ManageMainForm" id="ManageMainForm">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Comment ID</th>
                    <th>Purview ID</th>
                    <th>Sender ID</th>
                    <th>Sender Type</th>
                    <th>Massage Content</th>
                    <th>Send At</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($dataset)): ?>
                <?php if ($records_per_page === 'ALL'): ?>
                    <?php foreach ($dataset as $data): ?>
                        <tr>   
                            <td><input type="checkbox" name="selected_dataset[]" value="<?php echo $data['comment_id']?>"></td>
                            <td><?php echo htmlspecialchars($data['comment_id']) ?></td>
                            <td><?php echo htmlspecialchars($data['availability_id']) ?></td>
                            <td><?php echo htmlspecialchars($data['sender_id']) ?></td>
                            <td><?php echo htmlspecialchars($data['sender_type']) ?></td>
                            <td><?php echo htmlspecialchars($data['message']) ?></td>
                            <td><?php echo htmlspecialchars($data['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php for ($i = $start; $i < $start + $records_per_page && $i < count($dataset); $i++): ?>
                        <tr>   
                            <td><input type="checkbox" name="selected_dataset[]" value="<?php echo $dataset[$i]['comment_id']?>"></td>
                            <td><?php echo htmlspecialchars($dataset[$i]['comment_id']) ?></td>
                            <td><?php echo htmlspecialchars($dataset[$i]['availability_id']) ?></td>
                            <td><?php echo htmlspecialchars($dataset[$i]['sender_id']) ?></td>
                            <td><?php echo htmlspecialchars($dataset[$i]['sender_type']) ?></td>
                            <td><?php echo htmlspecialchars($dataset[$i]['message']) ?></td>
                            <td><?php echo htmlspecialchars($dataset[$i]['created_at']) ?></td>
                        </tr>
                    <?php endfor; ?>
                <?php endif; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Do Not Have Data at Now.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table> 
        <button type="submit" class="delete_btn" onclick="return confirmDelete()">Delete</button>
        <button onclick="printTable()" class="print_button">Print Table</button>
        <button onclick="saveAsPDF()" class="pdf_button">Save as PDF</button>
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
    
    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete the selected comments?");
    }
    
    function printTable() {
        const printContent = document.querySelector('table').outerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Print Table</title>');
        printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h1 style="text-align:center;">Teacher Comments Table</h1>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
    
    function saveAsPDF() {
        const element = document.querySelector('table');
        const opt = {
            margin: 10,
            filename: 'teacher_comments.pdf',
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
</body>
</html>