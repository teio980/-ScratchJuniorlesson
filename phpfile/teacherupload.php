<?php
include 'connect.php'; 


$title = $_POST['title'];
$description = $_POST['description'];


$sql = "INSERT INTO lessons (title, description, create_time) VALUES (?, ?, NOW())";

$stmt = $connect->prepare($sql);
$stmt->bind_param("ss", $title, $description);

if ($stmt->execute()) {
    echo "Lesson submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$connect->close();
?>
