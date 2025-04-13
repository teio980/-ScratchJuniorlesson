<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssfile/headeraf.css">
    <title>LK Scratch Kids</title>
</head>
<body>
    <nav>
        <ul class="side">
            <li onclick=hideSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26" viewBox="0 96 960 960" width="26"><path d="m249 849-42-42 231-231-231-231 42-42 231 231 231-231 42 42-231 231 231 231-42 42-231-231-231 231Z"/></svg></a></li>
            <li><a href="Main_page.php" class="spe">Home</a></li>
            <li><a href="../includes/logout.php" class="spe">Sign Out</a></li>
        </ul>
        <ul>
            <li><a href="Main_page.php"><strong>LK Scratch Kids</strong></a></li>
            <li><a href="Main_page.php" class="spe">Home</a></li>
            <li><a href="../includes/logout.php" class="spe">Sign Out</a></li>
            <li class="menu-button" onclick=showSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26" viewBox="0 96 960 960" width="26"><path d="M120 816v-60h720v60H120Zm0-210v-60h720v60H120Zm0-210v-60h720v60H120Z"/></svg></a></li>
        </ul>
    </nav>
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