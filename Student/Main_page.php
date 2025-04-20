<?php
    session_start();
    include '../phpfile/connect.php'; 
    include '../resheadAfterLogin.php';

    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['username'];

    $sql = "SELECT lesson_id, title, description,expire_date FROM lessons ORDER BY lesson_id ASC";
    $result = mysqli_query($connect, $sql);

    $sql_quiz = "SELECT DISTINCT difficult FROM questions ORDER BY difficult ASC";
    $result_quiz = mysqli_query($connect, $sql_quiz);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headeraf.css">
    <link rel="stylesheet" href="../cssfile/studentmain.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Profile Page</title>
</head>
<body>
<div class="tabs wrapper">
    <div class="tab active" data-tab="recent">EXERCISE</div>
    <div class="tab" data-tab="quiz">QUIZ</div>
    <div class="tab" data-tab="text">TEST1</div>
    <div class="tab" data-tab="shares">TEST2</div>
</div>

        <div id="popup" class="popup">
            <div class="popup-content">
                <p>Achieve over 80% to advance to the next level.</p>
                <button onclick="closePopup()">OK</button>
            </div>
        </div>

    <div class="container tab-content active" id="recent">
        <h2>Available Lessons</h2>
            <table border="1">
                <tr>
                    <th>Lesson</th> 
                    <th>Title</th>
                    <th>Description</th>
                    <th>Expired date</th>
                    <th>Action</th>
                </tr>

                <?php

                    // Start by checking if the student is already enrolled
                    $check_sql = "SELECT * FROM student_class WHERE student_id = ?";
                    $stmt = $connect->prepare($check_sql);
                    $stmt->bind_param("s", $user_id);
                    $stmt->execute();
                    $check_result = $stmt->get_result();

                    if ($check_result->num_rows === 0) {
                        // Not enrolled yet

                        // Handle form submission to enroll
                        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['class_id'])) {
                            $selected_class = $_POST['class_id'];

                            // Auto-generate student_class_id
                            $sql_sc_count = "SELECT COUNT(*) FROM student_class";
                            $stmt_sc = $connect->prepare($sql_sc_count);
                            $stmt_sc->execute();
                            $stmt_sc->bind_result($sc_count);
                            $stmt_sc->fetch();
                            $stmt_sc->close();

                            // Generate the new student_class_id in the format SC000001, SC000002, etc.
                            $new_student_class_id = 'SC' . str_pad($sc_count + 1, 6, '0', STR_PAD_LEFT);

                            // Insert the new record into student_class table
                            $insert_sql = "INSERT INTO student_class (student_class_id, student_id, class_id) VALUES (?, ?, ?)";
                            $insert_stmt = $connect->prepare($insert_sql);
                            $insert_stmt->bind_param("sss", $new_student_class_id, $user_id, $selected_class);

                            if ($insert_stmt->execute()) {
                                echo "<script>alert('You have successfully enrolled in a class.'); window.location.href = 'Main_page.php';</script>";
                            } else {
                                echo "<p>Error enrolling: " . $insert_stmt->error . "</p>";
                            }
                            $insert_stmt->close();
                        }

                        // Show class selection form if not already enrolled
                        $class_query = "SELECT class_id FROM class";
                        $class_result = mysqli_query($connect, $class_query);

                        echo "<div>";
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

                    $stmt->close();

    
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['lesson_id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['expire_date']}</td>
                                <td>
                                    <form action='studentsubmit.php' method='GET'>
                                        <input type='hidden' name='lesson_id' value='{$row['lesson_id']}'>
                                        <button type='submit'>Submit</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No lessons available</td></tr>";
                }?>
            </table>
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
                                                    <i class='fa fa-user' aria-hidden='true' style='font-size:80px;'></i>
                                                    <div class='xp-bar-container'>
                                                        <div class='xp-label'><p>Name: {$user_name}</p></div>
                                                        <div class='xp-bar'>
                                                            <div class='xp-fill' style='width: {$xp_percent}%;'></div>
                                                        </div>
                                                        <div class='xp-label'>LEVEL $current_level > $current_xp / $xp_needed XP</div>
                                                    </div>
                                                    <div class='progress-circle progressbar3' data-percentage='$xp_percent'>
                                                        <svg class='progress-ring' width='120' height='120'>
                                                            <circle class='progress-ring__circle-bg' stroke='#eee' stroke-width='8' fill='transparent' r='54' cx='60' cy='60'/>
                                                            <circle class='progress-ring__circle' stroke-width='8' fill='transparent' r='54' cx='60' cy='60'/>
                                                        </svg>
                                                        <div class='progress-text'>Lvl $current_level<br>$current_xp / $xp_needed XP</div>
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
                    <button class="btfour"><a href="ranking.php">View Ranking</a></button>
            </div>
        </div>
    </div>




</body>
</html>

<?php
$connect->close();
?>

<script>
    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.tab-content');
    let currentTabIndex = 0;

    tabs.forEach((tab, newIndex) => {
        tab.addEventListener('click', () => {
            if (tab.classList.contains('active') || newIndex === currentTabIndex) return;

            const direction = newIndex > currentTabIndex ? 'right' : 'left';
            const oldContent = contents[currentTabIndex];
            const newContent = contents[newIndex];

            contents.forEach(c => {
                c.classList.remove(
                    'tab-slide-in-left',
                    'tab-slide-in-right',
                    'tab-slide-out-left',
                    'tab-slide-out-right'
                );
            });

            oldContent.classList.add(
                direction === 'right' ? 'tab-slide-out-left' : 'tab-slide-out-right'
            );

            setTimeout(() => {
                oldContent.classList.remove('active');

                newContent.classList.add('active');
                newContent.classList.add(
                    direction === 'right' ? 'tab-slide-in-right' : 'tab-slide-in-left'
                );

                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                currentTabIndex = newIndex;
            }, 100);
        });
    });

        let startX = 0;
        let isDragging = false;

        document.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.clientX;
        });

        document.addEventListener('mouseup', (e) => {
        if (!isDragging) return;
        isDragging = false;

        const endX = e.clientX;
        const diffX = endX - startX;

        if (Math.abs(diffX) > 50) {
            const direction = diffX > 0 ? 'left' : 'right';
            const newIndex = direction === 'right' ? currentTabIndex + 1 : currentTabIndex - 1;

            if (newIndex >= 0 && newIndex < tabs.length) {
            tabs[newIndex].click();
            }
        }
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



</script>

    