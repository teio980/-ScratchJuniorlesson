<?php
include 'reshead.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="cssfile/header.css">
    <link rel="stylesheet" href="cssfile/aboutUs.css">
</head>
<body>
    <h1>About Us</h1>
    <div class="abt_flexbox">
        <div class="abt_box">
            <div><img class="abt_image" src="img_logo/LYL_parsonal_photo.jpg" ></div>
            <div class="abt_imgdesc">LIAW YONG LOON</div>
            <div class="abt_imgdesc">STUDENT ID: 1221208592</div>
        </div>
        <div class="abt_box">
            <div><img class="abt_image" src="img_logo/kwk.photo.jpg" ></div>
            <div class="abt_imgdesc">KONG WEN KHANG</div>
            <div class="abt_imgdesc">STUDENT ID: 1221206839</div>
        </div>
        <div class="abt_box">
            <div><img class="abt_image" src="img_logo/kbs.photo.jpg"></div>
            <div class="abt_imgdesc">KUM BING SHENG</div>
            <div class="abt_imgdesc">STUDENT ID: 1221206295</div>
        </div>
    </div>
</body>
<script>
    function showSidebar() {
        document.querySelector('.side').classList.add('show');
    }

    function hideSidebar() {
        document.querySelector('.side').classList.remove('show');
    }
</script>
</html>