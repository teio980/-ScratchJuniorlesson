<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssfile/reshead.css">
    <title>LK Scratch Kids</title>
</head>
<body>
    <nav>
        <ul class="side">
            <li onclick=hideSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26" viewBox="0 96 960 960" width="26"><path d="m249 849-42-42 231-231-231-231 42-42 231 231 231-231 42 42-231 231 231 231-42 42-231-231-231 231Z"/></svg></a></li>
            <li><a href="landingpage.php" class="spe">Home</a></li>
            <li><a href="AboutUs.php" class="spe">About Us</a></li>
            <li><a href="Course.php" class="spe">Course</a></li>
            <li><a href="contactUs.php" class="spe">Contact Us</a></li>
            <li><a href="login.php" class="spe">Sign In</a></li>
            <li><a href="register.php" class="spe">Create Account</a></li>
        </ul>
        <ul>
            <li><a href="#"><strong>LK Scratch Kids</strong></a></li>
            <li><a href="landingpage.php" class="hideOnMobile spe">Home</a></li>
            <li><a href="AboutUs.php" class="hideOnMobile spe">About Us</a></li>
            <li><a href="Course.php"class="hideOnMobile spe">Course</a></li>
            <li><a href="contactUs.php"class="hideOnMobile spe">Contact Us</a></li>
            <li><a href="login.php" class="hideOnMobile spe">Sign In</a></li>
            <li><a href="register.php" class="hideOnMobile spe">Create Account</a></li>
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