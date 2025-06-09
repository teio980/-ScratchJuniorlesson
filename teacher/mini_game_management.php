<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';
include 'resheadteacher.php';

$teacher_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'edit' && isset($_POST['game_id'])) {
        $game_id = $_POST['game_id'];
        $title = $_POST['title'];
        $get_old_image = "SELECT image_name FROM mini_games WHERE game_id = ?";
        $stmt = $connect->prepare($get_old_image);
        $stmt->bind_param("s", $game_id);
        $stmt->execute();
        $old_image = $stmt->get_result()->fetch_assoc()['image_name'];
        
        $fileName = $old_image;
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../phpfile/uploads_mini_games/';
            $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                if (file_exists($uploadDir . $old_image)) {
                    unlink($uploadDir . $old_image);
                }
            } else {
                $error_message = "Failed to upload new image. Keeping the old one.";
                $fileName = $old_image;
            }
        }
        
        $update_query = "UPDATE mini_games SET title = ?, image_name = ? WHERE game_id = ?";
        $stmt = $connect->prepare($update_query);
        $stmt->bind_param("sss", $title, $fileName, $game_id);
        
        if ($stmt->execute()) {
            $success_message = "Mini Game updated successfully!";
            header("Location: mini_game_management.php?success=1");
            exit();
        } else {
            $error_message = "Failed to update game information in database.";
        }
    } else {
        $title = $_POST['title'];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../phpfile/uploads_mini_games/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $fileExtension;
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                $last_id_query = "SELECT MAX(CAST(SUBSTRING(game_id, 2) AS UNSIGNED)) as max_id FROM mini_games";
                $result = $connect->query($last_id_query);
                $max_id = $result->fetch_assoc()['max_id'];
                $next_id = ($max_id ? $max_id + 1 : 1);
                $game_id = 'G' . str_pad($next_id, 6, '0', STR_PAD_LEFT);
                
                $insert_query = "INSERT INTO mini_games (game_id, teacher_id, title, image_name) 
                                VALUES (?, ?, ?, ?)";
                $stmt = $connect->prepare($insert_query);
                $stmt->bind_param("ssss", $game_id, $teacher_id, $title, $fileName);
                
                if ($stmt->execute()) {
                    $success_message = "Mini Game uploaded successfully!";
                    header("Location: mini_game_management.php?success=1");
                    exit();
                } else {
                    $error_message = "Failed to save game information to database.";
                    unlink($filePath);
                }
            } else {
                $error_message = "Failed to upload image.";
            }
        } else {
            $error_message = "Please select an image to upload.";
        }
    }
}

if (isset($_GET['delete']) && isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];
    
    $get_image_query = "SELECT image_name FROM mini_games WHERE game_id = ?";
    $stmt = $connect->prepare($get_image_query);
    $stmt->bind_param("s", $game_id);
    $stmt->execute();
    $image_name = $stmt->get_result()->fetch_assoc()['image_name'];
    
    $delete_query = "DELETE FROM mini_games WHERE game_id = ?";
    $stmt = $connect->prepare($delete_query);
    $stmt->bind_param("s", $game_id);
    
    if ($stmt->execute()) {
        $filePath = '../phpfile/uploads_mini_games/' . $image_name;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $success_message = "Mini Game deleted successfully!";
        header("Location: mini_game_management.php?deleted=1");
        exit();
    } else {
        $error_message = "Failed to delete game from database.";
    }
}

$games_query = "SELECT * FROM mini_games WHERE teacher_id = ? ORDER BY create_time DESC";
$stmt = $connect->prepare($games_query);
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$games_result = $stmt->get_result();

if (isset($_GET['success'])) {
    $success_message = "Operation completed successfully!";
}
if (isset($_GET['deleted'])) {
    $success_message = "Mini Game deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Game Management</title>
    <link rel="stylesheet" href="../cssfile/Tmain.css">
    <link rel="stylesheet" href="../cssfile/resheadteacher.css">
    <link rel="stylesheet" href="../cssfile/mini_games.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Mini Game Management</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <h2>Upload New Mini Game</h2>
        <div class="upload-form">
            <form method="POST" enctype="multipart/form-data" id="gameForm">
                <input type="hidden" name="action" id="formAction" value="new">
                <input type="hidden" name="game_id" id="editGameId" value="">
                <div>
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div>
                    <label for="image">Image (16:9 aspect ratio required):</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                    <div id="fileNameDisplay" class="file-name-display"></div>
                    <div id="imagePreviewContainer" style="display:none; margin-top:10px;">
                        <img id="imagePreview" src="#" alt="Preview" style="max-width:100%;">
                    </div>
                </div>
                <button type="submit" id="submitBtn">Upload Mini Game</button>
            </form>
        </div>
        
        <h2>Your Mini Games</h2>
        <div class="game-container">
            <?php if ($games_result->num_rows > 0): ?>
                <?php while ($game = $games_result->fetch_assoc()): ?>
                    <div class="game-card">
                        <button class="delete-btn" onclick="confirmDelete('<?php echo $game['game_id']; ?>')">
                            <i class="fas fa-times"></i>
                        </button>
                        <button class="edit-btn" onclick="openEditModal(
                            '<?php echo $game['game_id']; ?>',
                            '<?php echo htmlspecialchars($game['title'], ENT_QUOTES); ?>'
                        )">
                            <i class="fas fa-edit"></i>
                        </button>
                        <img src="../phpfile/uploads_mini_games/<?php echo htmlspecialchars($game['image_name']); ?>" 
                             alt="<?php echo htmlspecialchars($game['title']); ?>">
                        <h3><?php echo htmlspecialchars($game['title']); ?></h3>
                        <small>Uploaded: <?php echo date('Y-m-d', strtotime($game['create_time'])); ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You haven't uploaded any mini games yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Edit Mini Game</h2>
            <form method="POST" enctype="multipart/form-data" id="editForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="modalGameId" name="game_id" value="">
                <div>
                    <label for="editTitle">Title:</label>
                    <input type="text" id="editTitle" name="title" required>
                </div>
                <div>
                    <label for="editImage">New Image (optional):</label>
                    <input type="file" id="editImage" name="image" accept="image/*">
                </div>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="../javascriptfile/mini_games.js"></script>
</body>
</html>