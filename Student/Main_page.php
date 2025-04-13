<?php
    session_start();
    include '../phpfile/connect.php'; 
    include '../resheadAfterLogin.php';

    $user_id = $_SESSION['user_id'];

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
                        while ($row = $result_quiz->fetch_assoc()) {
                            $difficulty = $row['difficult'];

                            $sql_correct = "SELECT COUNT(*) as correct FROM student_answers 
                                            JOIN questions ON student_answers.question_id = questions.id
                                            WHERE student_answers.student_id = $user_id 
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

                            $percen = $total_questions > 0 ? round(($total_correct / $total_questions) * 100, 2) : 0;
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
                    } else {
                        echo "<div class='quiz-card'><div class='text'><h3>No quiz data</h3><p>Please check back later.</p></div></div>";
                    }
                    ?>
                </div>
            </div>
            <div class="quizcurve"></div>

            <div class="quizright">
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
</script>

    