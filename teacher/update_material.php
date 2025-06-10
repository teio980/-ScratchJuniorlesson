<?php
require_once '../includes/check_session_teacher.php';
include '../phpfile/connect.php';
include '../includes/connect_DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_material'])) {
    $material_id = $_POST['material_id'];
    $title = $_POST['material_title'];
    $description = $_POST['material_description'];
    $class_id = $_POST['class_id'];
    
    // Get current file info
    $sql = "SELECT file_name FROM teacher_materials WHERE material_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $material = $result->fetch_assoc();
    
    $old_file_name = $material['file_name'];
    $new_file_name = $old_file_name;
    
    // Handle file update
    if (!empty($_FILES['material_file']['name'])) {
        // Delete old file
        $old_file_path = "../phpfile/upload_teacher_material/" . $old_file_name;
        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }
        
        // Generate new filename
        $original_filename = basename($_FILES['material_file']['name']);
        $new_file_name = str_replace(' ', '_', $original_filename);
        move_uploaded_file($_FILES['material_file']['tmp_name'], "../phpfile/upload_teacher_material/" . $new_file_name);
    }
    
    // Update database
    $update_sql = "UPDATE teacher_materials SET 
                  title = ?, 
                  description = ?, 
                  class_id = ?,
                  file_name = ? 
                  WHERE material_id = ?";
                  
    $stmt = $connect->prepare($update_sql);
    $stmt->bind_param("sssss", $title, $description, $class_id, $new_file_name, $material_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Material updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update material!";
    }
    header("Location: lesson_management.php?tab=materials");
    exit();
}
?>