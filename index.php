<?php include 'reshead.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
    <link rel="stylesheet" href="cssfile/reshead.css">
    <link rel="stylesheet" href="cssfile/first.css">
</head>
<body>
    <div class="stay">
        <div class="text">
            <h1>Welcome to ScratchJunior LK Scratch Kids</h1>
            <p>Our website is designed to help young students learn ScratchJunior in a fun and interactive way. We provide step-by-step tutorials, engaging lessons, and hands-on exercises to build coding skills.</p>
            <a href="#" class="learnmore">Learn more</a>
        </div>
    </div>
    <div class="service">
        <h1>What we Offer</h1>
        <p>Learn, Practice, and Improve with ScratchJunior</p>

            <div class="courselist">
                <div class="course">
                    <h3>ScratchJunior Lessons</h2>
                    <p>Step-by-step tutorials to <br>help young learners brunderstand ScratchJunior in a fun way.</p>
                </div>
                <div class="course">
                    <h3>Interactive Quizzes</h2>
                    <p>Engaging quizzes to test understanding and reinforce learning.</p>
                </div>
                <div class="course">
                    <h3>Submission Checking</h2>
                    <p>Students can submit their projects for review and feedback.</p>
                </div>
        </div>
    </div>
    <script>
const courses = document.querySelectorAll(".course");

function checkScroll() {
    const windowHeight = window.innerHeight;
    
    courses.forEach((course, index) => {
        const rect = course.getBoundingClientRect();
        
        // Calculate how much to delay each course (each step is 25% of viewport height)
        let step = (index + 1) * 0.15; // 1st = 25%, 2nd = 50%, 3rd = 75%
        let progress = (windowHeight - rect.top) / (windowHeight * step);

        progress = Math.min(Math.max(progress, 0), 1); // Clamp between 0 and 1

        // Apply smooth sliding and fading
        course.style.opacity = progress;
        course.style.transform = `translateX(${(1 - progress) * -500}px)`; // Moves from left to right
    });
}

// Listen for scroll events
window.addEventListener("scroll", checkScroll);
checkScroll(); // Run on load in case elements are already in view

    </script>
</body>
</html>