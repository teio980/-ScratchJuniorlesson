<?php
include 'connect.php'; 

if (isset($_POST["savebtn"])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $expire_date = $_POST['expire_date'];

    $sql = "INSERT INTO lessons (title, description, expire_date, create_time) VALUES (?, ?, ?, NOW())";

    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sss", $title, $description, $expire_date);

    if ($stmt->execute()) {
        echo "Lesson submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$connect->close();
?>
