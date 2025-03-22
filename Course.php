<?php
include 'reshead.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Course</h1>
    
    <div class="level-buttons">
        <a href="prepare.php?level=easy" class="level-button">Easy</a>
        <a href="prepare.php?level=medium" class="level-button">Medium</a>
        <a href="prepare.php?level=hard" class="level-button">Hard</a>
    </div>

    <script>
        function showSidebar() {
            document.querySelector('.side').classList.add('show');
        }

        function hideSidebar() {
            document.querySelector('.side').classList.remove('show');
        }
    </script>
</body>
</html>