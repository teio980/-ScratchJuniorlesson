<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssfile/header.css">
    <title>ScratchJUnior lesson</title>
</head>
<body>
    <nav>
        <ul class="side">
            <li onclick=hideSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26" viewBox="0 96 960 960" width="26"><path d="m249 849-42-42 231-231-231-231 42-42 231 231 231-231 42 42-231 231 231 231-42 42-231-231-231 231Z"/></svg></a></li>
            <li><a href="landingpage.html" class="spe">Home</a></li>
            <li><a href="AboutUs.html" class="spe">About Us</a></li>
            <li><a href="Course.html" class="spe">Course</a></li>
            <li><a href="contactUs.html" class="spe">Contact Us</a></li>
            <li><a href="login.html" class="spe">Sign In</a></li>
            <li><a href="register.html" class="spe">Create Account</a></li>
        </ul>
        <ul>
            <li><a href="#">ScratchJunior</a></li>
            <li><a href="landingpage.html" class="hideOnMobile spe">Home</a></li>
            <li><a href="AboutUs.html" class="hideOnMobile spe">About Us</a></li>
            <li><a href="Course.html"class="hideOnMobile spe">Course</a></li>
            <li><a href="contactUs.html"class="hideOnMobile spe">Contact Us</a></li>
            <li><a href="login.html" class="hideOnMobile spe">Sign In</a></li>
            <li><a href="register.html" class="hideOnMobile spe">Create Account</a></li>
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