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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

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
 
</body>
<script>
  gsap.registerPlugin(ScrollTrigger);

  gsap.utils.toArray(".course").forEach(course => {
    gsap.from(course, {
      scrollTrigger: {
        trigger: course,
        start: "top 90%",
        end: "top 60%",
        scrub: true
      },
      x: -200,
      opacity: 0
    });
  });
</script>

</html>