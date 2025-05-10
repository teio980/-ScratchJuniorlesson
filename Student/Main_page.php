<?php
    require_once '../includes/check_session_student.php';
    include '../phpfile/connect.php'; 
    include '../includes/connect_DB.php';

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

    $SelectUserSql = "SELECT S_Username AS Username, S_Mail AS Mail FROM student WHERE student_id LIKE :ID";
    $SelectUserStmt = $pdo->prepare($SelectUserSql);
    $SelectUserStmt->bindValue(':ID', $user_id );
    $SelectUserStmt->execute();
    $user = $SelectUserStmt->fetch(PDO::FETCH_ASSOC);

    $checkStudentClassSql = "SELECT COUNT(*) FROM student_class WHERE student_id = :studentID";
    $checkStudentClassStmt = $pdo->prepare($checkStudentClassSql);
    $checkStudentClassStmt->bindParam(':studentID', $user_id);
    $checkStudentClassStmt->execute();
    $hasClass = (bool)$checkStudentClassStmt->fetchColumn();

    $selectClassSql = "SELECT class_id, class_code, class_name, class_description AS Description, max_capacity AS Max, current_capacity AS Cur FROM class WHERE max_capacity > current_capacity";
    $selectClassStmt = $pdo->prepare($selectClassSql);
    $selectClassStmt->execute();
    $classes = $selectClassStmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($classes as $class) {
        $teacher_sql = "SELECT t.T_Username
                        FROM teacher_class tc
                        JOIN teacher t ON tc.teacher_id = t.teacher_id
                        WHERE tc.class_id = :class_id";
        
        $stmt = $pdo->prepare($teacher_sql);
        $stmt->bindValue(':class_id', $class['class_id']);
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $class['teachers'] = $teachers;
        $result[] = $class;
    }

    $getOldClassSql = "SELECT c.class_id,c.class_code,class_name
                        FROM student_class sc
                        JOIN class c ON sc.class_id = c.class_id
                        WHERE sc.student_id = :S_ID";
    $getOldClassStmt = $pdo->prepare($getOldClassSql);
    $getOldClassStmt->bindParam(':S_ID',$user_id);
    $getOldClassStmt->execute();
    $oldClass = $getOldClassStmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        echo "<script>
        alert('$message');
        </script>";
        
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssfile/studentheader.css">
    <link rel="stylesheet" href="../cssfile/studentmain.css">
    <link rel="stylesheet" href="../cssfile/personal_profile.css">
    <link rel="stylesheet" href="../cssfile/enrollClass.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Profile Page</title>
</head>
<body>
    <nav id="sidebar" class="ss">
        <ul>
        <li>
            <span class="logo">Lk Scratch Kids</span>
            <button onclick=toggleSidebar() id="toggle-btn">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z"/></svg>
            </button>
        </li>
        <li class="active">
            <a href="#" class="sidebar-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24px" height="24px"><path d="M96 0C43 0 0 43 0 96L0 416c0 53 43 96 96 96l288 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64c17.7 0 32-14.3 32-32l0-320c0-17.7-14.3-32-32-32L384 0 96 0zm0 384l256 0 0 64L96 448c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16zm16 48l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/></svg>
            <span>Notes</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24px" height="24px"><path d="M96 0C43 0 0 43 0 96L0 416c0 53 43 96 96 96l288 0 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64c17.7 0 32-14.3 32-32l0-320c0-17.7-14.3-32-32-32L384 0 96 0zm0 384l256 0 0 64L96 448c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16zm16 48l192 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-192 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/></svg>
            <span>Exercise</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24px" height="24px"><path d="M481 31C445.1-4.8 386.9-4.8 351 31l-15 15L322.9 33C294.8 4.9 249.2 4.9 221.1 33L135 119c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0L255 66.9c9.4-9.4 24.6-9.4 33.9 0L302.1 80 186.3 195.7 316.3 325.7 481 161c35.9-35.9 35.9-94.1 0-129.9zM293.7 348.3L163.7 218.3 99.5 282.5c-48 48-80.8 109.2-94.1 175.8l-5 25c-1.6 7.9 .9 16 6.6 21.7s13.8 8.1 21.7 6.6l25-5"/></svg>
            <span>Marked Exercise</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link">
            <svg viewBox="0 0 800 800" width="24" height="24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><g><path d="M676.637,183.386c0.002-0.002,0.004-0.004,0.005-0.005L522.549,29.287c-3.619-3.62-8.62-5.86-14.145-5.86H137.5 c-11.046,0-20,8.954-20,20v713.146c0,11.046,8.954,20,20,20h525c11.046,0,20-8.954,20-20V197.522 C682.5,192.407,680.426,187.203,676.637,183.386z M642.5,736.573h-485V63.427h342.62l114.096,114.095l-85.812,0v-41.788 c0-11.046-8.954-20-20-20s-20,8.954-20,20v61.788c0,11.046,8.954,20,20,20c0,0,92.404,0,134.096,0V736.573z"></path><path d="M295.217,224.417l-39.854,39.855l-5.697-5.697c-7.811-7.811-20.473-7.811-28.283,0c-7.811,7.81-7.811,20.473,0,28.284 l19.84,19.84c3.75,3.751,8.838,5.858,14.142,5.858c5.305,0,10.392-2.107,14.143-5.858l53.996-53.999 c7.81-7.811,7.81-20.474-0.001-28.284C315.69,216.606,303.027,216.606,295.217,224.417z"></path><path d="M557.831,312.557h6.646c11.046,0,20-8.954,20-20s-8.954-20-20-20h-6.646c-11.046,0-20,8.954-20,20 S546.785,312.557,557.831,312.557z"></path><path d="M367.389,272.557c-11.046,0-20,8.954-20,20s8.954,20,20,20h129.609c11.046,0,20-8.954,20-20s-8.954-20-20-20H367.389z"></path><path d="M557.831,435.552h6.646c11.046,0,20-8.954,20-20s-8.954-20-20-20h-6.646c-11.046,0-20,8.954-20,20 S546.785,435.552,557.831,435.552z"></path><path d="M496.998,395.552H367.389c-11.046,0-20,8.954-20,20s8.954,20,20,20h129.609c11.046,0,20-8.954,20-20 S508.044,395.552,496.998,395.552z"></path><path d="M557.831,558.547h6.646c11.046,0,20-8.954,20-20s-8.954-20-20-20h-6.646c-11.046,0-20,8.954-20,20 S546.785,558.547,557.831,558.547z"></path><path d="M496.998,518.547H367.389c-11.046,0-20,8.954-20,20s8.954,20,20,20h129.609c11.046,0,20-8.954,20-20 S508.044,518.547,496.998,518.547z"></path><path d="M557.831,681.542h6.646c11.046,0,20-8.954,20-20s-8.954-20-20-20h-6.646c-11.046,0-20,8.954-20,20 S546.785,681.542,557.831,681.542z"></path><path d="M496.998,641.542H367.389c-11.046,0-20,8.954-20,20s8.954,20,20,20h129.609c11.046,0,20-8.954,20-20 S508.044,641.542,496.998,641.542z"></path><path d="M255.363,435.552c5.304,0,10.392-2.107,14.142-5.858l53.996-53.996c7.811-7.811,7.811-20.475,0-28.285 s-20.473-7.811-28.283,0l-39.854,39.855l-5.697-5.698c-7.81-7.81-20.474-7.812-28.284-0.001s-7.811,20.474-0.001,28.284 l19.84,19.841C244.972,433.444,250.059,435.552,255.363,435.552z"></path><path d="M234.239,511.547l-12.856,12.857c-7.81,7.811-7.81,20.474,0.001,28.284c3.905,3.905,9.023,5.857,14.142,5.857 s10.237-1.952,14.143-5.858l12.855-12.855l12.856,12.855c3.904,3.906,9.023,5.858,14.142,5.858s10.237-1.952,14.142-5.858 c7.811-7.811,7.811-20.473,0-28.283l-12.855-12.857l12.856-12.857c7.81-7.811,7.81-20.474-0.001-28.284 c-7.811-7.81-20.474-7.81-28.284,0.001l-12.856,12.856l-12.857-12.856c-7.811-7.811-20.473-7.811-28.283,0s-7.811,20.474,0,28.283 L234.239,511.547z"></path><path d="M295.217,593.4l-39.854,39.855l-5.697-5.697c-7.811-7.811-20.473-7.811-28.283,0c-7.811,7.81-7.811,20.473,0,28.283 l19.84,19.84c3.75,3.752,8.838,5.858,14.142,5.858c5.305,0,10.392-2.107,14.143-5.858l53.996-53.998 c7.81-7.811,7.81-20.474-0.001-28.284C315.69,585.59,303.027,585.59,295.217,593.4z"></pa</g></g></svg>
            <span>Quiz</span>
            </a>
        </li>
        <li>
            <a href="#" class="sidebar-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24px" height="24px"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg>    
            <span>Profile</span>
            </a>
        </li>

        <li>
            <a href="#" class="sidebar-link">
            <svg height="24px" width="24px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#ffffff" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><style type="text/css">.st0{fill:#ffffff;}</style><g><path class="st0" d="M81.44,116.972c23.206,0,42.007-18.817,42.007-42.008c0-23.215-18.801-42.016-42.007-42.016 c-23.216,0-42.016,18.801-42.016,42.016C39.424,98.155,58.224,116.972,81.44,116.972z"></path><path class="st0" d="M224.166,245.037c0-0.856-0.142-1.673-0.251-2.498l62.748-45.541c3.942-2.867,4.83-8.411,1.963-12.362 c-1.664-2.285-4.342-3.652-7.17-3.652c-1.877,0-3.667,0.589-5.191,1.689l-62.874,45.636c-2.341-1.068-4.909-1.704-7.65-1.704 h-34.178l-8.294-47.222c-4.555-23.811-14.112-42.51-34.468-42.51h-86.3C22.146,136.873,0,159.019,0,179.383v141.203 c0,10.178,8.246,18.432,18.424,18.432c5.011,0,0,0,12.864,0l7.005,120.424c0,10.83,8.788,19.61,19.618,19.61 c8.12,0,28.398,0,39.228,0c10.83,0,19.61-8.78,19.61-19.61l9.204-238.53h0.463l5.27,23.269c1.744,11.097,11.293,19.28,22.524,19.28 h51.534C215.92,263.461,224.166,255.215,224.166,245.037z M68.026,218.861v-67.123h24.126v67.123l-12.817,15.118L68.026,218.861z"></path><polygon class="st0" points="190.326,47.47 190.326,200.869 214.452,200.869 214.452,71.595 487.874,71.595 487.874,302.131 214.452,302.131 214.452,273.113 190.326,273.113 190.326,326.256 512,326.256 512,47.47 "></polygon><path class="st0" d="M311.81,388.597c0-18.801-15.235-34.029-34.028-34.029c-18.801,0-34.036,15.228-34.036,34.029 c0,18.785,15.235,34.028,34.036,34.028C296.574,422.625,311.81,407.381,311.81,388.597z"></path><path class="st0" d="M277.781,440.853c-24.259,0-44.866,15.919-52.782,38.199h105.565 C322.648,456.771,302.04,440.853,277.781,440.853z"></path><path class="st0" d="M458.573,388.597c0-18.801-15.235-34.029-34.028-34.029c-18.801,0-34.036,15.228-34.036,34.029 c0,18.785,15.235,34.028,34.036,34.028C443.338,422.625,458.573,407.381,458.573,388.597z"></path><path class="st0" d="M424.545,440.853c-24.259,0-44.866,15.919-52.783,38.199h105.565 C469.411,456.771,448.804,440.853,424.545,440.853z"></path></g></g></svg>
            <span>Enroll</span>
            </a>
        </li>
                
        <li>
            <a href="#" class="sidebar-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="24px" width="24px" fill="#e8eaed"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z"/></svg>
            <span>Change Class</span>
            </a>
        </li>

        </ul>
    </nav>
    <main>
        <?php include 'resheadstudent.php'; ?>
        <div class="mm">
        <!-- Teaching Notes Page -->
        <div class="container tab-content active" id="exercise">
                
        </div>

        <!-- Exercise Page -->
        <div class="container tab-content active" id="exercise">
            <?php
            $check_sql = "SELECT class_id FROM student_class WHERE student_id = '$user_id'";
            $check_result = mysqli_query($connect, $check_sql);

            if (mysqli_num_rows($check_result) > 0) {
                $row = mysqli_fetch_assoc($check_result);
                $class_id = $row['class_id'];

                echo "
                    <div class='classuidwrapper'>
                        <div class='classuid'>Class ID: <strong>$class_id</strong></div>
                    </div>
                ";

                // ========== Submitted Section ==========
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

                if (mysqli_num_rows($result_submitted) > 0) {
                    echo '<div class="section-divider"><span class="section-title">Submitted</span></div>';
                    echo '<div class="exercisewrapper">';

                    $current_date = date('Y-m-d');
                    $counter = 0;

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

                    $is_expired = strtotime($expire_date) < strtotime($current_date);
                    $button_html = $is_expired
                        ? '<button class="buttonsubmit disabled" disabled>Deadline Passed</button>'
                        : '<button class="buttonsubmit"><a href="studentsubmit.php?availability_id=' . $row['availability_id'] . '">Edit Submission</a></button>';

                    echo '
                    <div class="card project-card" style="background: ' . $bg_style . '">
                        <div class="lang-tag">Submitted</div>
                        <div class="circle"></div>
                        <div class="title">' . $lesson_title . '</div>
                        <div class="expireddate">Due: ' . $expire_date . '</div>
                        ' . $button_html . '
                    </div>';
                } 
                echo '</div>';
            }

                // ========== Not Yet Submitted ==========
                $sql_work = "SELECT availability_id, student_work, expire_date, lesson_id FROM class_work WHERE class_id = '$class_id'";
                $result_work = mysqli_query($connect, $sql_work);

                $pending_cards = '';
                $counter = 0;
                $current_date = date('Y-m-d'); 

                if ($result_work && mysqli_num_rows($result_work) > 0) {
                    while ($row = mysqli_fetch_assoc($result_work)) {
                        $lesson_id = $row['lesson_id'];

                        $check_submission = "SELECT 1 FROM student_submit WHERE student_id = '$user_id' AND lesson_id = '$lesson_id' AND class_id = '$class_id'";
                        $result_submission = mysqli_query($connect, $check_submission);
                        if (mysqli_num_rows($result_submission) > 0) continue;

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
                            "linear-gradient(to bottom right, #ff5e62, #ff9966)",
                            "linear-gradient(to bottom right, #4a90e2, #5cd2e6)",
                            "linear-gradient(to bottom right, #f8b500, #fceabb)",
                        ];
                        $bg_style = $backgrounds[$counter % count($backgrounds)];
                        $counter++;

                        $is_expired = strtotime($expire_date) < strtotime($current_date);
                        $button_html = $is_expired
                            ? '<button class="buttonsubmit disabled" disabled>Deadline Passed</button>'
                            : '<button class="buttonsubmit"><a href="studentsubmit.php?availability_id=' . $row['availability_id'] . '">Submit</a></button>';

                        $pending_cards .= '
                        <div class="card project-card" style="background: ' . $bg_style . '">
                            <div class="lang-tag">Scratch Junior</div>
                            <div class="circle"></div>
                            <div class="title">' . $lesson_title . '</div>
                            <div class="expireddate">' . $expire_date . '</div>
                            ' . $button_html . '
                        </div>';
                    }
                }

                if (!empty($pending_cards)) {
                    echo '<div class="section-divider"><span class="section-title">Not Yet Submitted</span></div>';
                    echo '<div class="exercisewrapper">' . $pending_cards . '</div>';
                }

                // ========== Unavailable Section ==========
                $sql_all_lessons = "SELECT lesson_id, title FROM lessons";
                $result_all_lessons = mysqli_query($connect, $sql_all_lessons);

                $unavailable_cards = '';
                if ($result_all_lessons && mysqli_num_rows($result_all_lessons) > 0) {
                    $existing_lessons = [];
                    $sql_existing = "SELECT lesson_id FROM class_work WHERE class_id = '$class_id'";
                    $result_existing = mysqli_query($connect, $sql_existing);
                    while ($row = mysqli_fetch_assoc($result_existing)) {
                        $existing_lessons[] = $row['lesson_id'];
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

                            $unavailable_cards .= '
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

                if (!empty($unavailable_cards)) {
                    echo '<div class="section-divider"><span class="section-title">Unavailable</span></div>';
                    echo '<div class="exercisewrapper">' . $unavailable_cards . '</div>';
                }

            } else {
                echo "
                    <div class='classuidwrapper'>
                        <div class='classuid'>You are not enrolled in any class yet. <strong>Please enroll a class.</strong></div>
                    </div>
                ";
            }
            ?>
        </div>

        <!-- Marked Feedback Section -->
        <div class="container tab-content" id="marked">
            <div class="flr">
                <?php
                $feedback_query = "
                    SELECT ssf.rating, ssf.comments, ssf.created_at, ss.filename 
                    FROM student_submit_feedback ssf
                    INNER JOIN student_submit ss ON ssf.submit_id = ss.submit_id
                    WHERE ss.student_id = '$user_id'
                    ORDER BY ssf.created_at DESC
                ";

                $feedback_result = mysqli_query($connect, $feedback_query);

                if ($feedback_result && mysqli_num_rows($feedback_result) > 0) {
                    while ($row = mysqli_fetch_assoc($feedback_result)) {
                        $filename = htmlspecialchars($row['filename']);
                        $file_path = "../Student/uploads/" . $filename;
                        $rating = htmlspecialchars($row['rating']);
                        $comments = htmlspecialchars($row['comments']);
                        $marked_time = htmlspecialchars($row['created_at']);

                        echo '
                        <div class="card project-card">
                            <div class="tittle">Submitted File: <br><a href="' . $file_path . '" target="_blank">' . $filename . '</a></div>
                            <div class="rating" data-rating="' . $rating . '">Rating: <strong></strong><span class="stars"></span></div>                        
                            <div class="comments">Comments: ' . $comments . '</div>
                            <div class="marked-time">Marked on: ' . $marked_time . '</div>
                        </div>';
                    }
                } else {
                    echo "<p>No marked homework available yet.</p>";
                }
                ?>
            </div>
        </div>

        <!--Quiz Page--> 
        <div class="container tab-content" id="quiz">
            <div id="popup" class="popup">
                <div class="popup-content">
                    <p>Achieve over 80% to advance to the next level.</p>
                    <button onclick="closePopup()">OK</button>
                </div>
            </div>
            <?php
                // Initialize required variables
                $progress_circle_1 = "";
                $progress_circle_2 = "";
                $progress_circle_3 = "";
                $quiz_cards_html = "";

                // XP & level (progress_circle_3)
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

                $next_level = $current_level + 1;
                $emojiLevels = getEmojiLevels();
                $nextEmojis = isset($emojiLevels[$next_level]) ? implode(' ', $emojiLevels[$next_level]) : '';

                $progress_circle_3 = "
                    <div class='xp-section'>
                        <img src='$fullPath' alt='Avatar' class='Avatar'>
                        <div class='xp-bar-container'>
                            <div class='xp-label'><p>Name: {$user_name}</p></div>
                            <div class='xp-bar'>
                                <div class='xp-fill' style='width: {$xp_percent}%;'></div>
                            </div>
                            <div class='xp-label'>LEVEL $current_level > $current_xp / $xp_needed XP</div>
                            <div class='xp-label'>Level up to unlock: $nextEmojis more... </div>
                        </div>
                    </div>
                ";


                // Quiz data & progress (progress_circle_1, progress_circle_2, and quiz cards)
                $total_correct_all = 0;
                $total_questions_all = 0;
                $total_quizzes = 0;
                $completed_quizzes = 0;
                $previous = true;

                if ($result_quiz->num_rows > 0) {
                    while ($row = $result_quiz->fetch_assoc()) {
                        $difficulty = $row['difficult'];
                        $total_quizzes++;

                        // Correct answers
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

                        // Total questions
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
                            $button = "<button class='bttwo popup-trigger'>Start Quiz</button>";
                        }

                        $status_text = 'Incomplete';
                        $status_class = 'status-unavailable';
                        
                        if ($previous) {
                            if ($percen >= 80) {
                                $status_text = 'Completed';
                                $status_class = 'status-completed';
                            } else {
                                $status_text = 'Available';
                                $status_class = 'status-available';
                            }
                        }

                        $icons = ['📕', '📘', '📗', '📙', '📒', '📔', '📚', '📓'];
                        $icon = $icons[array_rand($icons)];
                        
                        $quiz_cards_html .= "
                            <div class='quiz-item'>
                                <div class='quiz-icon'>$icon</div>
                                <div class='quiz-details'>
                                    <h4 class='quiz-title'>Quiz $difficulty</h4>
                                    <p class='quiz-status $status_class'>$status_text</p>
                                    <p class='quiz-score'>Score: $score_display</p>
                                    <p class='quiz-percent'>$percen%</p>
                                </div>
                                <div class='quiz-action'>$button</div>
                            </div>
                            <hr class='quiz-item-underline'>
                        ";
                        
                    }

                    // Build progress_circle_1 (Overall %)
                    $overall_percent = $total_questions_all > 0 ? round(($total_correct_all / $total_questions_all) * 100, 2) : 0;
                    $progress_circle_1 = "
                        <div class='progresswrapper progresswrapper1'>
                            <div class='progress-label'>Overall Percentage <br>for whole quiz: $overall_percent%</div>
                            <div class='progress-circle progressbar1' data-percentage='$overall_percent'>
                                <svg class='progress-ring' width='120' height='120'>
                                    <circle class='progress-ring__circle-bg' stroke='#eee' stroke-width='6' fill='transparent' r='40' cx='45' cy='45'/>
                                    <circle class='progress-ring__circle' stroke-width='6' fill='transparent' r='40' cx='45' cy='45'/>
                                </svg>
                                <div class='progress-text'>$overall_percent%</div>
                            </div>
                        </div>
                    ";

                    // Build progress_circle_2 (Completed quizzes)
                    $quiz_percent = $total_quizzes > 0 ? round(($completed_quizzes / $total_quizzes) * 100, 2) : 0;
                    $progress_circle_2 = "
                        <div class='progresswrapper progresswrapper2'>
                            <div class='progress-label'>Quizzes <br>Completed: $completed_quizzes out of $total_quizzes</div>
                            <div class='progress-circle progressbar2' data-percentage='$quiz_percent'>
                                <svg class='progress-ring' width='120' height='120'>
                                    <circle class='progress-ring__circle-bg' stroke='#eee' stroke-width='6' fill='transparent' r='40' cx='45' cy='45'/>
                                    <circle class='progress-ring__circle' stroke-width='6' fill='transparent' r='40' cx='45' cy='45'/>
                                </svg>
                                <div class='progress-text'>$completed_quizzes / $total_quizzes</div>
                            </div>
                        </div>
                    ";
                }
            ?>
            <?php
                $result = $connect->query("SELECT level FROM student_level WHERE student_id = '$user_id'");
                $row = $result->fetch_assoc();
                $user_level = $row ? $row['level'] : 1;

                function getEmojiLevels() {
                    return [
                        1 => [
                            '😊', '😂', '😃', '😉', '😅', '🙂', '😁', '😄', '😆', '🙃',
                            '😇', '😋', '😜', '😝', '🤪', '😛', '🤗', '🤭', '🫢', '😺',
                            '😸', '😹', '😽', '🙀', '😻', '😼', '😎', '🫠', '😬', '😌',
                            '😴', '🥱', '🤤', '😪', '🤓', '🫶', '👐', '👏', '✌️', '👍'
                        ],
                        2 => ['😎', '😍', '😘', '😋', '😺', '😻'],
                        3 => ['🤯', '😈', '😤', '🥵', '🧐', '😳'],
                        4 => ['🚀', '🎉', '💥', '⚡', '🎈', '🌟'],
                        5 => ['🔥', '👑', '🥇', '🏆', '🦄', '💎', '🧠'],
                        6 => ['💡', '📚', '🧪', '🧬', '🛠️', '🔍'],
                        7 => ['🎮', '🕹️', '🧩', '🏹', '⚔️', '🛡️'],
                        8 => ['🌈', '🌌', '🪐', '🌍', '🛰️', '☄️'],
                        9 => ['🎭', '🎨', '🎵', '🎤', '📸', '🎬'],
                        10 => ['🫅', '💼', '🌟', '🥂', '🏛️', '💰', '🕊️']
                    ];
                }


                function getUnlockedEmojis($level) {
                    $emojiLevels = getEmojiLevels();
                    $unlockedEmojis = [];
                    foreach ($emojiLevels as $lvl => $emojis) {
                        if ($level >= $lvl) {
                            $unlockedEmojis = array_merge($unlockedEmojis, $emojis);
                        }
                    }
                    return $unlockedEmojis;
                }

                $emojiLevels = getEmojiLevels();
                $unlockedEmojis = getUnlockedEmojis($user_level);

                $allEmojis = [];
                foreach ($emojiLevels as $level => $emojis) {
                    foreach ($emojis as $emoji) {
                        $allEmojis[$emoji] = $level;
                    }
                }
            ?>
            <div class="quizbox">
                <div class="quizright">
                    <div class="progress-xp-box">
                        <?php echo $progress_circle_3; ?>
                    </div>
                    <div class="quiz-container">
                        <h1>Quiz</h1>
                        <?php echo $quiz_cards_html; ?>
                    </div>
                </div>
                <div class="quizleft">
                    <div class="progress-duo-box">
                        <?php echo $progress_circle_1; ?>
                        <?php echo $progress_circle_2; ?>
                    </div>
                    <div class="chatbox">
                        <form class="chat-input" method="POST" action="../phpfile/livechat.php">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />

                        <div class="input-with-emoji">
                            <input type="text" name="message" placeholder="Type your message..." required />
                            <button type="button" id="emoji-icon" class="emoji-icon"><i class="fas fa-smile"></i></button>
                        </div>

                        <div class="emoji-wrapper">
                            <div id="emoji-picker" class="emoji-picker" style="display: none;">
                            <?php foreach ($allEmojis as $emoji => $requiredLevel): ?>
                                <?php $isUnlocked = in_array($emoji, $unlockedEmojis); ?>
                                <button 
                                type="button"
                                class="emoji-btn<?php echo $isUnlocked ? '' : ' locked'; ?>"
                                title="<?php echo $isUnlocked ? '' : 'Unlock at level ' . $requiredLevel; ?>"
                                <?php echo $isUnlocked ? '' : 'disabled'; ?>>
                                <?php echo $emoji; ?>
                                </button>
                            <?php endforeach; ?>
                            </div>
                        </div>

                        <button type="submit" class="sendbtn">Send</button>
                        </form>

                        <div class="chat-messages">
                            <?php
                            $sql = "SELECT student_livechat.*, student.S_Username FROM student_livechat
                                    JOIN student ON student_livechat.student_id = student.student_id
                                    ORDER BY student_livechat.createtime DESC";
                            $result = mysqli_query($connect, $sql);
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                $chat_class = ($row['student_id'] == $user_id) ? 'user' : 'other';
                                echo "<div class='chat-message $chat_class'>";
                                echo "<strong>" . htmlspecialchars($row['S_Username']) . ":</strong> ";
                                echo htmlspecialchars($row['chat']);
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>        
            </div>
            <button class="btfour"><a href="ranking.php?difficult=">View Ranking</a></button>
        </div>
        
        <!--Profile Page-->
        <div class="container tab-content" id="profile">
            <h1>Personal Profile</h1>
            <form action="../includes/change_Avatar.php" class="avatar_container" method="post" enctype="multipart/form-data">
            <input type="file" name="change_Avatar" id="change_Avatar" accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="this.form.submit()">
            <label for="change_Avatar" style="cursor: pointer;">
                <img src="<?php echo $fullPath; ?>" alt="Avatar" class="avatar">
            </label>
            </form>
            <form action="../includes/change_Username.php" method="post" class="changeUsernameEmail_box">
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user_id) ?>">
                <div class="UsernameEmail_box">
                <label for="new_Username">Username(6-12 Characters):</label>
                <input type="text" name="new_Username" id="new_Username"  minlength="6" maxlength="12" required value="<?php echo htmlspecialchars($user['Username']) ?>">
                </div>

                <div class="UsernameEmail_box">
                <label for="new_Mail">E-mail:</label>
                <input type="email" name="new_Mail" id="new_Mail" required  value="<?php echo htmlspecialchars($user['Mail']) ?>">
                </div>

                <button type="submit" class="save_btn">Save Changes</button>
            </form>
            <form action="../includes/change_Password.php" method="post" class="changePassword_box">
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user_id) ?>">

                <div class="password_box">
                <label for="old_Password">Old Password:</label>
                <input type="password" name="old_Password" id="old_Password" required>
                <span class="material-symbols-outlined" id="showOldPassword_icon" onclick="showPassword('old_Password','showOldPassword_icon') ">visibility_off</span>
                </div>

                <div class="password_box">
                <label for="new_Password">New Password:</label>
                <input type="password" name="new_Password" id="new_Password" required>
                <span class="material-symbols-outlined" id="showNewPassword_icon" onclick="showPassword('new_Password','showNewPassword_icon') ">visibility_off</span>
                </div>

                <div class="password_box">
                <label for="new_Password">Confirmed New Password:</label>
                <input type="password" name="confirmed_new_Password" id="confirmed_new_Password" required>
                <span class="material-symbols-outlined" id="showNewConfirmedPassword_icon" onclick="showPassword('confirmed_new_Password','showNewConfirmedPassword_icon') ">visibility_off</span>
                </div>

                <button type="submit" class="save_btn">Save Changes</button>
            </form>
        </div>

        <!--Enroll Page-->                
        <div class="container tab-content active" id="exroll">
            <div class="enroll_box">
            <?php foreach ($result as $class): ?>
                <form action="../includes/process_enroll_class.php" class="enroll_form" method="post">
                
                    <input type="hidden" name="Max" value="<?php echo htmlspecialchars($class['Max']) ?>">
                    <input type="hidden" name="Current" value="<?php echo htmlspecialchars($class['Cur']) ?>">
                    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user_id)?>">
                    <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class['class_id']) ?>">

                    <div class="enrollment_container">
                        <div><img src="path_to_class_image" alt="Class Image"></div>
                        <div>
                            <h4><?php echo htmlspecialchars($class['class_name']) ?></h4>
                            <p>Teach by: <?php 
                                $teacherNames = array();
                                foreach ($class['teachers'] as $teacher) {
                                    $teacherNames[] = htmlspecialchars($teacher['T_Username']);
                                }
                            ?></p>
                            <p><?php echo htmlspecialchars($class['Description']) ?></p>
                            <div class="capacity_box">
                                <div>Max Capacity: <?php echo htmlspecialchars($class['Max']) ?></div>
                                <div>Current: <?php echo htmlspecialchars($class['Cur']) ?></div>
                            </div>
                            <div ><button type="submit" class="enroll_btn" name ="enroll_btn">Enroll</button></div>
                        </div>
                    </div> 
                </form>
            <?php endforeach; ?>
            </div>
        </div>

        <!--Change Class Page-->
        <div class="container tab-content" id="recent">
            <form action="../includes/process_change_Class.php" method="post" class="changeClass_box">
                <input type="hidden" name="S_ID" value="<?php echo htmlspecialchars($user_id); ?>">
                <div class="Class_box">
                    <label for="old_class">Change from class:</label>
                    <select name="old_class" id="old_class">
                    <?php foreach ($oldClass as $class): ?>
                        <option value="<?php echo htmlspecialchars($class['class_code']); ?>">
                        <?php echo htmlspecialchars($class['class_code'] . ' ' . $class['class_name']); ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                </div>


                <div class="Class_box">
                    <label for="class_option">Change to class:</label>
                    <select name="class_option" id="class_option" required>
                    <option value="">-- Select a Class --</option>
                    <?php
                    $oldClassCodes = array_column($oldClass, 'class_code');
                    ?>
                    <?php foreach ($classes as $class): ?>
                        <?php if (in_array($class['class_code'], $oldClassCodes)) continue; ?>
                            <option value="<?php echo htmlspecialchars($class['class_code']); ?>">
                                    <?php echo htmlspecialchars($class['class_code'] . ' ' . $class['class_name']); ?>
                            </option>
                    <?php endforeach; ?>
                    </select>
                </div>

                <div class="Class_box">
                <label for="changeClassReason">Reason:</label>
                <textarea name="changeClassReason" id="changeClassReason" rows="1" style="height: 50px;"></textarea>
                </div>

                <button type="submit" class="save_btn">Send Request</button>
            </form>
        </div>

    </main>             

</body>
</html>

<?php
$connect->close();
?>

<script>
    //Enroll Page JS
    const hasClass = <?php echo $hasClass ? 'true' : 'false'; ?>;

    if (hasClass) {
        document.querySelectorAll('.enroll_btn').forEach(button => {
            button.style.display = 'none';
        });
    }

    //Profile Page JS
    function showPassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (passwordInput && icon) {
        if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = 'visibility';
        } else {
        passwordInput.type = 'password';
        icon.textContent = 'visibility_off';
        }
    }
    }

    document.querySelector('form.changePassword_box').addEventListener('submit', function(e) {
    const newPass = document.getElementById('new_Password').value;
    const confirmPass = document.getElementById('confirmed_new_Password').value;

    if (newPass !== confirmPass) {
        e.preventDefault(); 
        alert('New Password does not match Confirmed Password!');
        return false;
    }

    return true;
    });

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

    //rating star
    document.querySelectorAll('.rating').forEach(el => {
        const rating = parseFloat(el.dataset.rating);
        const starCount = rating / 2;
        let starsHTML = '';

        for (let i = 1; i <= 5; i++) {
            if (i <= Math.floor(starCount)) {
                starsHTML += '<i class="fas fa-star"></i>'; // full star
            } else if (i - 0.5 <= starCount) {
                starsHTML += '<i class="fas fa-star-half-alt"></i>'; // half star
            } else {
                starsHTML += '<i class="far fa-star"></i>'; // empty star
            }
        }

        el.querySelector('.stars').innerHTML = starsHTML;
    });

    //emoji popup

const emojiIcon = document.getElementById('emoji-icon');
const emojiPicker = document.getElementById('emoji-picker'); // Keep only one declaration
const messageInput = document.querySelector('input[name="message"]');
const emojiButtons = document.querySelectorAll('.emoji-btn');

// Handle scroll direction when mouse wheel is used inside the emoji picker
emojiPicker.addEventListener('wheel', (event) => {
    event.preventDefault(); // Prevent the default scroll behavior (vertical scrolling)

    // If scrolling up (wheel delta < 0), scroll left
    if (event.deltaY < 0) {
        emojiPicker.scrollBy({ left: -100, behavior: 'smooth' });
    }
    // If scrolling down (wheel delta > 0), scroll right
    else {
        emojiPicker.scrollBy({ left: 100, behavior: 'smooth' });
    }
});

// Toggle the emoji picker when emoji icon is clicked
emojiIcon.addEventListener('click', () => {
    const isPickerVisible = emojiPicker.style.display === 'block';
    emojiPicker.style.display = isPickerVisible ? 'none' : 'block';
});

// Insert emoji into message input when clicked
emojiButtons.forEach(button => {
    button.addEventListener('click', () => {
        const emoji = button.textContent.replace(/\s+/g, '');  // Clean up any extra spaces

        messageInput.value = messageInput.value.trim() + emoji;  // Add emoji to input

        emojiPicker.style.display = 'none'; // Close picker after selection
    });
});

// Close the emoji picker if the user clicks outside of it
document.addEventListener('click', (event) => {
    if (!emojiIcon.contains(event.target) && !emojiPicker.contains(event.target)) {
        emojiPicker.style.display = 'none';  // Hide picker if click is outside
    }
});










</script>

        