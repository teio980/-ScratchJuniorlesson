<?php
    require_once '../includes/check_session_student.php';
    include '../phpfile/connect.php'; 


    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['username'];

    $sql = "SELECT class_id FROM student_class WHERE student_id = '$user_id'";
    $result = mysqli_query($connect, $sql);

    $sql_quiz = "SELECT DISTINCT difficult FROM questions ORDER BY difficult ASC";
    $result_quiz = mysqli_query($connect, $sql_quiz);

    $Avatar_directory = "Avatar/";
    $files = scandir($Avatar_directory);
    $fileFound = false;
    foreach ($files as $file) {
        if ($file != "." && $file != "..") { 
            $avatar = pathinfo($file, PATHINFO_FILENAME);
            
            if ($avatar == $user_id) {
                $fullPath = $Avatar_directory . DIRECTORY_SEPARATOR . $file; 
                $fileFound = true;
                break; 
            }else{
                $fullPath = 'Avatar/avatar_default.png';
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../cssfile/studentheader.css">
    <link rel="stylesheet" href="../cssfile/studentmain.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Profile Page</title>
</head>
<body>
        <li class="active">
    <nav id="sidebar" class="ss">
        <ul>
        <li>
            <span class="logo">Lk Scratch Kids</span>
            <button onclick=toggleSidebar() id="toggle-btn">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z"/></svg>
            </button>
        </li>
            <a href="#" class="sidebar-link">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-40q0-17 11.5-28.5T280-880q17 0 28.5 11.5T320-840v40h320v-40q0-17 11.5-28.5T680-880q17 0 28.5 11.5T720-840v40h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z"/></svg>
            <span>Exercise</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link">
            <span>Quiz</span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M520-640v-160q0-17 11.5-28.5T560-840h240q17 0 28.5 11.5T840-800v160q0 17-11.5 28.5T800-600H560q-17 0-28.5-11.5T520-640ZM120-480v-320q0-17 11.5-28.5T160-840h240q17 0 28.5 11.5T440-800v320q0 17-11.5 28.5T400-440H160q-17 0-28.5-11.5T120-480Zm400 320v-320q0-17 11.5-28.5T560-520h240q17 0 28.5 11.5T840-480v320q0 17-11.5 28.5T800-120H560q-17 0-28.5-11.5T520-160Zm-400 0v-160q0-17 11.5-28.5T160-360h240q17 0 28.5 11.5T440-320v160q0 17-11.5 28.5T400-120H160q-17 0-28.5-11.5T120-160Zm80-360h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/></svg>
            </a>
        </li>
        
        <li>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-240v-32q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v32q0 33-23.5 56.5T720-160H240q-33 0-56.5-23.5T160-240Zm80 0h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z"/></svg>
            <span>Profile</span>
            </a>
            <a href="#" class="sidebar-link">
        </li>
        </ul>
   </nav>
    <div><a href="enroll_class.php">enroll_class</a></div>
    <div id="popup" class="popup">
        <div class="popup-content">
            <p>Achieve over 80% to advance to the next level.</p>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>

    <main>
    <div class="mm">
        <?php include 'resheadstudent.php'; ?>

    <div class="container tab-content active" id="recent">

<?php
    $check_sql = "SELECT * FROM student_class WHERE student_id = '$user_id'";
    $check_result = mysqli_query($connect, $check_sql);

    if (mysqli_num_rows($check_result) === 0) {
        if (isset($_POST['class_id'])) {
            $selected_class = mysqli_real_escape_string($connect, $_POST['class_id']);


            $sql_sc_count = "SELECT COUNT(*) as count FROM student_class";
            $result_sc = mysqli_query($connect, $sql_sc_count);
            $row = mysqli_fetch_assoc($result_sc);
            $sc_count = $row['count'];

            $new_student_class_id = 'SC' . str_pad($sc_count + 1, 6, '0', STR_PAD_LEFT);

            $insert_sql = "INSERT INTO student_class (student_class_id, student_id, class_id) 
                        VALUES ('$new_student_class_id', '$user_id', '$selected_class')";
            if (mysqli_query($connect, $insert_sql)) {
                echo "<script>alert('You have successfully enrolled in a class.'); window.location.href = 'Main_page.php';</script>";
            } else {
                echo "<p>Error enrolling: " . mysqli_error($connect) . "</p>";
            }
        }

        $class_query = "SELECT class_id FROM class";
        $class_result = mysqli_query($connect, $class_query);

        echo "<div class='classuidwrapper'>";
        echo "<h3>Select Your Class</h3>";
        echo "<form method='POST'>";
        echo "<select name='class_id' required>";
        while ($row = mysqli_fetch_assoc($class_result)) {
            echo "<option value='{$row['class_id']}'>{$row['class_id']}</option>";
        }
        echo "</select>";
        echo "<br><br><button type='submit'>Enroll</button>";
        echo "</form>";
        echo "</div>";
    }
    $sql_class = "SELECT class_id FROM student_class WHERE student_id = '$user_id'";
    $result_class = mysqli_query($connect, $sql_class);
    if ($result_class && mysqli_num_rows($result_class) > 0) {
        $row_class = mysqli_fetch_assoc($result_class);
        $class_id = $row_class['class_id'];

        echo "
            <div class='classuidwrapper'>
                <div class='classuid'>Class ID: <strong>$class_id</strong></div>
            </div>
        ";
    }
?>

<div class="section-divider">
    <span class="section-title">Submitted</span>
</div>
<div class="exercisewrapper">
    <?php
        $sql_submitted = "
        SELECT cw.availability_id, ss.filename AS student_work, cw.expire_date
        FROM class_work cw
        INNER JOIN student_submit ss 
            ON cw.class_id = ss.class_id 
            AND cw.lesson_id = ss.lesson_id
        WHERE cw.class_id = '$class_id'
            AND ss.student_id = '$user_id'
        ";
        $result_submitted = mysqli_query($connect, $sql_submitted);
        $counter = 0;
        if (mysqli_num_rows($result_submitted) > 0) {
        while ($row = mysqli_fetch_assoc($result_submitted)) {
            $student_work = htmlspecialchars($row['student_work']);
            $expire_date = htmlspecialchars($row['expire_date']);

            $sql_lesson = "SELECT title FROM lessons WHERE lesson_file_name = '$student_work' LIMIT 1";
            $result_lesson = mysqli_query($connect, $sql_lesson);

            $lesson_title = $student_work;
            if ($result_lesson && mysqli_num_rows($result_lesson) > 0) {
                $lesson_row = mysqli_fetch_assoc($result_lesson);
                $lesson_title = htmlspecialchars($lesson_row['title']);
            }

            $backgrounds = [
                "linear-gradient(to bottom right, #6dd5ed, #2193b0)",
                "linear-gradient(to bottom right, #ff758c, #ff7eb3)",
                "linear-gradient(to bottom right, #43cea2, #185a9d)",
            ];
            $bg_style = $backgrounds[$counter % count($backgrounds)];
            $counter++;

            echo '
            <div class="card project-card" style="background: ' . $bg_style . '">
                <div class="lang-tag">Submitted</div>
                <div class="circle"></div>
                <div class="title">' . $lesson_title . '</div>
                <div class="expireddate">' . $expire_date . '</div>
                <button class="buttonsubmit">
                    <a href="studentsubmit.php?availability_id=' . $row['availability_id'] . '">Edit Submission</a>
                </button>
            </div>';
        }
        }
    ?>
</div>

<!-- Section: Not Yet Submitted -->
<div class="section-divider">
    <span class="section-title">Not Yet Submitted</span>
</div>
<div class="exercisewrapper">
    <?php
    $sql_work = "SELECT availability_id, student_work, expire_date, lesson_id FROM class_work WHERE class_id = '$class_id'";
    $result_work = mysqli_query($connect, $sql_work);

    $counter = 0;
    if (mysqli_num_rows($result_work) > 0) {
        while ($row = mysqli_fetch_assoc($result_work)) {
            $lesson_id = $row['lesson_id'];
            $student_work = htmlspecialchars($row['student_work']);
            $expire_date = htmlspecialchars($row['expire_date']);

            $check_submission = "SELECT * FROM student_submit WHERE student_id = '$user_id' AND lesson_id = '$lesson_id' AND class_id = '$class_id'";
            $result_submission = mysqli_query($connect, $check_submission);

            if (mysqli_num_rows($result_submission) > 0) continue;

            $sql_lesson = "SELECT title FROM lessons WHERE lesson_file_name = '$student_work' LIMIT 1";
            $result_lesson = mysqli_query($connect, $sql_lesson);

            $lesson_title = $student_work;
            if ($result_lesson && mysqli_num_rows($result_lesson) > 0) {
                $lesson_row = mysqli_fetch_assoc($result_lesson);
                $lesson_title = htmlspecialchars($lesson_row['title']);
            }

            $backgrounds = [
                "linear-gradient(to bottom right, #ff5e62, #ff9966)",
                "linear-gradient(to bottom right, #4a90e2, #5cd2e6)", 
                "linear-gradient(to bottom right, #f8b500, #fceabb)",
            ];
            $bg_style = $backgrounds[$counter % count($backgrounds)];
            $counter++;

            echo '
            <div class="card project-card" style="background: ' . $bg_style . '">
                <div class="lang-tag">Scratch Junior</div>
                <div class="circle"></div>
                <div class="title">' . $lesson_title . '</div>
                <div class="expireddate">' . $expire_date . '</div>
                <button class="buttonsubmit">
                    <a href="studentsubmit.php?availability_id=' . $row['availability_id'] . '">Submit</a>
                </button>
            </div>';
        }
    }
    ?>
</div>

<!-- Section: Unavailable -->
<div class="section-divider">
    <span class="section-title">Unavailable</span>
</div>
<div class="exercisewrapper">
    <?php
    $sql_all_lessons = "SELECT lesson_id, title FROM lessons";
    $result_all_lessons = mysqli_query($connect, $sql_all_lessons);

    if (mysqli_num_rows($result_all_lessons) > 0) {
        $existing_lessons = [];
        $sql_existing = "SELECT lesson_id FROM class_work WHERE class_id = '$class_id'";
        $result_existing = mysqli_query($connect, $sql_existing);
        if ($result_existing) {
            while ($row = mysqli_fetch_assoc($result_existing)) {
                $existing_lessons[] = $row['lesson_id'];
            }
        }

        $counter = 0;

        while ($lesson = mysqli_fetch_assoc($result_all_lessons)) {
            $lesson_id = $lesson['lesson_id'];
            $lesson_title = htmlspecialchars($lesson['title']);

            if (!in_array($lesson_id, $existing_lessons)) {
                $backgrounds = [
                    "linear-gradient(to bottom right, #bdc3c7, #2c3e50)",
                    "linear-gradient(to bottom right, #7f8c8d, #95a5a6)",
                    "linear-gradient(to bottom right, #e0e0e0, #c0c0c0)",
                ];
                $bg_style = $backgrounds[$counter % count($backgrounds)];
                $counter++;

                echo '
                <div class="card project-card" style="background: ' . $bg_style . '">
                    <div class="lang-tag">Unavailable</div>
                    <div class="circle"></div>
                    <div class="title">' . $lesson_title . '</div>
                    <div class="expireddate">N/A</div>
                    <button class="buttonsubmit" disabled><a>Unavailable</a></button>
                </div>';
            }
        }
    }
    ?>
</div>
</div>


<div class="container tab-content" id="quiz">
<div class="quizbox">
    <div class="quizleft">
        <div class="quiz-container">
            <?php
            if ($result_quiz->num_rows > 0) {
                $previous = true;

                $total_correct_all = 0;
                $total_questions_all = 0;

                $total_quizzes = 0;
                $completed_quizzes = 0;

                while ($row = $result_quiz->fetch_assoc()) {
                    $difficulty = $row['difficult'];
                    $total_quizzes++;

                    $sql_correct = "SELECT COUNT(*) as correct FROM student_answers 
                                    JOIN questions ON student_answers.question_id = questions.id
                                    WHERE student_answers.student_id = '$user_id'
                                    AND questions.difficult = '$difficulty' 
                                    AND student_answers.is_correct = 1";
                    $result_correct = mysqli_query($connect, $sql_correct);

                    $total_correct = 0;
                    if ($result_correct->num_rows > 0) {
                        $row_correct = $result_correct->fetch_assoc();
                        $total_correct = $row_correct['correct'];
                    }

                    $sql_total = "SELECT COUNT(*) as total FROM questions WHERE difficult = '$difficulty'";
                    $result_total = mysqli_query($connect, $sql_total);
                    $total_questions = 0;
                    if ($result_total->num_rows > 0) {
                        $row_total = $result_total->fetch_assoc();
                        $total_questions = $row_total['total'];
                    }

                    $total_correct_all += $total_correct;
                    $total_questions_all += $total_questions;

                    $percen = $total_questions > 0 ? round(($total_correct / $total_questions) * 100, 2) : 0;

                    if ($percen >= 80) {
                        $completed_quizzes++;
                    }
                    
                    $score_display = "$total_correct / $total_questions";
                    $active_class = $previous ? "quiz-card active" : "quiz-card";

                    if ($previous) {
                        if ($percen >= 80) {
                            $icon = "<i class='fa fa-check' style='color:green; margin-right: 8px;'></i>";
                            $button = "<button disabled class='btthree'>Completed</button>";
                            $previous = true;
                        } else {
                            $icon = "<i class='fa fa-check' style='color:green; margin-right: 8px;'></i>";
                            $button = "<button class='btone'><a href='Questionpaper.php?difficult=$difficulty'>Start Quiz</a></button>";
                            $previous = false;
                        }
                    } else {
                        $icon = "<i class='fa fa-lock' style='color:gray; margin-right: 8px;'></i>";
                        $button = "<button class='bttwo popup-trigger'>Unavailable</button>";
                    }

                    echo "<div class='$active_class'>
                            <i class='fa fa-book' aria-hidden='true'></i>
                            <div class='text'>
                                <h3>$icon Quiz $difficulty</h3>
                                <p>$score_display • $percen%</p>
                            </div>
                            
                            $button
                        </div>";
                }
                $overall_percent = $total_questions_all > 0 ? round(($total_correct_all / $total_questions_all) * 100, 2) : 0;
                $progress_circle_1 = "<div class='progresswrapper progresswrapper1'>
                                        <div class='progress-label'>Overall Percentage <br>for whole quiz: $overall_percent%</div>
                                        <div class='progress-circle progressbar1' data-percentage='$overall_percent'>
                                            <svg class='progress-ring' width='120' height='120'>
                                                <circle class='progress-ring__circle-bg' stroke='#eee' stroke-width='8' fill='transparent' r='54' cx='60' cy='60'/>
                                                <circle class='progress-ring__circle' stroke-width='8' fill='transparent' r='54' cx='60' cy='60'/>
                                            </svg>
                                            <div class='progress-text'>$overall_percent%</div>
                                        </div>
                                    </div>";


                $quiz_percent = $total_quizzes > 0 ? round(($completed_quizzes / $total_quizzes) * 100, 2) : 0;
                $progress_circle_2 = "<div class='progresswrapper progresswrapper2'>
                                        <div class='progress-label'>Quizzes <br>Completed: $completed_quizzes out of $total_quizzes</div>
                                        <div class='progress-circle progressbar2' data-percentage='$quiz_percent'>
                                            <svg class='progress-ring' width='120' height='120'>
                                                <circle class='progress-ring__circle-bg' stroke='#eee' stroke-width='8' fill='transparent' r='54' cx='60' cy='60'/>
                                                <circle class='progress-ring__circle' stroke-width='8' fill='transparent' r='54' cx='60' cy='60'/>
                                            </svg>
                                            <div class='progress-text'>$completed_quizzes / $total_quizzes</div>
                                        </div>
                                    </div>";
                                    

                                    $xp_sql = "SELECT experience, level FROM student_level WHERE student_id = '$user_id'";
                                    $xp_result = mysqli_query($connect, $xp_sql);
                                    
                                    $current_xp = 0;
                                    $current_level = 1;
                                    
                                    if ($xp_result && mysqli_num_rows($xp_result) > 0) {
                                        $xp_row = mysqli_fetch_assoc($xp_result);
                                        $current_xp = (int)$xp_row['experience'];
                                        $current_level = (int)$xp_row['level'];
                                    }
                                    
                                    $xp_needed = 100 + ($current_level - 1) * 50;
                                    $xp_percent = $xp_needed > 0 ? round(($current_xp / $xp_needed) * 100, 2) : 0;
                                    
                                    $progress_circle_3 = "
                                        <div class='xp-section'>
                                            <img src='$fullPath' alt='Avatar' class='Avatar'>
                                            <div class='xp-bar-container'>
                                                <div class='xp-label'><p>Name: {$user_name}</p></div>
                                                <div class='xp-bar'>
                                                    <div class='xp-fill' style='width: {$xp_percent}%;'></div>
                                                </div>
                                                <div class='xp-label'>LEVEL $current_level > $current_xp / $xp_needed XP</div>
                                            </div>
                                        </div>
                                    ";   
            }
            ?>
        </div>
    </div>

    <div class="quizcurve"></div>

    <div class="quizright">
            <div class="progress-xp-box">
                <?php 
                    echo $progress_circle_3;
                ?>
            </div>
            <div class="progress-duo-box">
                <?php 
                    echo $progress_circle_1;
                    echo $progress_circle_2;
                ?>
            </div>
            <button class="btfour"><a href="ranking.php?difficult=">View Ranking</a></button>
    </div>
</div>
</div>
    </div>


    </main>             

</body>
</html>

<?php
$connect->close();
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebarLinks = document.querySelectorAll('.sidebar-link'); // Sidebar links
    const sidebarItems = document.querySelectorAll('#sidebar ul li'); // Sidebar <li> items (all of them)
    const tabContents = document.querySelectorAll('.tab-content'); // Tab containers

    // Ensure the first sidebar link and tab content are active by default
    sidebarLinks[0].classList.add('active'); // Adding active to the first sidebar link
    sidebarItems[1].classList.add('active'); // Adding active to the second li (starting from the sidebar links)
    tabContents[0].classList.add('active'); // Make sure the first tab content is shown

    sidebarLinks.forEach((link, index) => {
        link.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default link behavior

            // Remove 'active' class from all sidebar links and <li> items
            sidebarLinks.forEach(link => link.classList.remove('active'));
            sidebarItems.forEach(item => item.classList.remove('active'));

            // Add 'active' class to the clicked sidebar link and its parent <li>
            link.classList.add('active');
            sidebarItems[index + 1].classList.add('active'); // Add 'active' to the corresponding <li> (skip the first item)

            // Remove 'active' from all tab containers
            tabContents.forEach(content => content.classList.remove('active'));

            // Add 'active' class to the corresponding tab content (index matches sidebar)
            if (tabContents[index]) {
                tabContents[index].classList.add('active');
            }
        });
    });
});


    /*pop out window */
    document.querySelectorAll('.popup-trigger').forEach(button => {
        button.addEventListener('click', () => {
            alert("Achieve over 80% to advance to the next level.");
        });
    });

    /*circle color */

    document.querySelectorAll('.progress-circle').forEach(circle => {
    const percent = parseFloat(circle.getAttribute('data-percentage'));
    const radius = 54;  // match the circle's radius
    const circumference = 2 * Math.PI * radius;
    const progress = circle.querySelector('.progress-ring__circle');
    const text = circle.querySelector('.progress-text');

    // Set initial stroke dasharray and dashoffset for the progress circle
    progress.style.strokeDasharray = `${circumference}`;
    progress.style.strokeDashoffset = circumference;

    let currentPercent = 0;
    const stepTime = 10; // Update every 10ms for smooth animation
    const increment = percent / 100; // Small increment for each step

    const updateProgress = () => {
        if (currentPercent <= percent) {
            // Calculate strokeDashoffset based on current percentage
            const offset = circumference - (currentPercent / 100) * circumference;
            progress.style.strokeDashoffset = offset;
            text.textContent = `${Math.round(currentPercent)}%`;  // Update number inside circle

            currentPercent += increment;  // Increase the percentage
            requestAnimationFrame(updateProgress);  // Request next frame
        } else {
            // Once animation is complete, ensure the final value is set
            text.textContent = `${percent}%`;
        }
    };

    // Start the animation
    updateProgress();
    });

//sidebar
const toggleButton = document.getElementById('toggle-btn')
const sidebar = document.getElementById('sidebar')

function toggleSidebar(){
  sidebar.classList.toggle('close')
  toggleButton.classList.toggle('rotate')

  closeAllSubMenus()
}

function toggleSubMenu(button){

  if(!button.nextElementSibling.classList.contains('show')){
    closeAllSubMenus()
  }

  button.nextElementSibling.classList.toggle('show')
  button.classList.toggle('rotate')

  if(sidebar.classList.contains('close')){
    sidebar.classList.toggle('close')
    toggleButton.classList.toggle('rotate')
  }
}

function closeAllSubMenus(){
  Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
    ul.classList.remove('show')
    ul.previousElementSibling.classList.remove('rotate')
  })
}

</script>

    