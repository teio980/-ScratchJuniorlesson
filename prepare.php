<?php
include 'reshead.php';
$level = $_GET['level']; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prepare</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Get Ready!</h1>
    <p>Starting in <span id="countdown">3</span> seconds...</p>

    <script src="tutorial.js"></script>
    <script>
        let countdown = 3;
        const countdownElement = document.getElementById('countdown');

        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown === 0) {
                clearInterval(timer);
                window.location.href = `question.php?level=<?php echo $level; ?>&question=1`;
            }
        }, 1000);
    </script>
</body>
</html>