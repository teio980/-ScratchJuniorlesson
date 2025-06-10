<?php
require_once '../includes/check_session_admin.php';
include '../includes/connect_DB.php';
include 'header_Admin.php';

$getMassageUnreadSql = "SELECT student_id FROM student_change_class WHERE status = 'pending';";
$getMassageUnreadStmt = $pdo->prepare($getMassageUnreadSql);
$getMassageUnreadStmt->execute();
$unreadMessage = $getMassageUnreadStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/headerAdmin.css">
    <link rel="stylesheet" href="../cssfile/adminMenu.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Admin</title>
</head>
<body>
    <div class="menu_container">
        <ul class="adminMenu">
        <li><a href="manageUser.php">User</a></li>
        <li><a href="manageClass.php">Class</a></li>
        <li><a href="viewClassPerformance.php">Class Perforamance</a></li>
        <li><a href="manageChangeClass.php">Change Class Requests</a></li>
        <li><a href="teacher_comments.php">Teacher Comments</a></li>
        <li><a href="student_livechat.php">Student Chat</a></li>
        <li><a href="teaching_materials.php">Teaching Materials</a></li>
        <li><a href="quiz.php">Quiz</a></li>
        <li><a href="lessons.php">Task</a></li>
        <li><a href="task_purview.php">Task Purview</a></li>
        <li><a href="puzzle.php">Puzzle</a></li>
        <li><a href="student_quiz_progress.php">Student Quiz Progress</a></li>
        <li><a href="student_puzzle_progress.php">Student Puzzle Progress</a></li>
        <li><a href="student_submission.php">Student Submission</a></li>
        <li><a href="admin_profile.php">Personal Profile</a></li>
        <li></li>
        </ul>
    </div>
    <div class="chart_container">
    <canvas id="passChart" width="450" height="350" ></canvas>
    <canvas id="userChart" width="700" height="350"></canvas>
    </div>
    <?php foreach ($unreadMessage as $message): ?>
    <div class="notification_container">
        <h2>Change Class Request</h2>
        <div>You have received a change class request send from <?php echo htmlspecialchars($message['student_id'])?>.</div>
    </div>
    <?php endforeach; ?>
</body>
<script>
fetch("../includes/passing_rate.php")
  .then(res => res.json())
  .then(data => {
    const ctx = document.getElementById("passChart").getContext("2d");
    new Chart(ctx, {
      type: "pie",
      data: {
        labels: ["pass(=>40%)", "fail(<40%)"],
        datasets: [{
          data: [data.passed_students, data.total_students - data.passed_students],
          backgroundColor: ["#4CAF50", "#F44336"]
        }]
      },
      options: {
        responsive: false,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: `Passing Rate for all student:${data.pass_rate}%`,
            color: 'black', 
            font: {
              size: 16, 
              family: 'Arial', 
              weight: 'bold' 
            }
          },
          legend: {
          labels: {
            color: 'black',
            font: {
              size: 14,
              family: 'Arial'
            }
          }
        }
        }
      }
    });
  });

fetch("../includes/new_user.php")
    .then(response => response.json())
    .then(data => {
        const labels = data.map(item => item.month);
        const counts = data.map(item => item.count);

        const ctx = document.getElementById('userChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'New Users Registered',
                    data: counts,
                    borderColor: 'blue',
                    backgroundColor: 'lightblue',
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
              responsive: false,
              maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Users'
                        },
                        ticks: {
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            },
                        }
                    }
                }
            }
        });
    })
    .catch(error => console.error('Error loading data:', error));
</script>
</html>