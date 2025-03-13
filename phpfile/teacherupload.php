<?php
include 'connect.php'; 

if (isset($_POST["savebtn"])) {
$title = $_POST['title'];
$description = $_POST['description'];


$sql = "INSERT INTO lessons (title, description, create_time) VALUES (?, ?, NOW())";

$stmt = $connect->prepare($sql);
$stmt->bind_param("ss", $title, $description);

if ($stmt->execute()) {
    echo "Lesson submitted successfully!";
} 

$stmt->close();
}
$connect->close();
?>
