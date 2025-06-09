<?php
    require_once '../includes/check_session_student.php';
    include '../phpfile/connect.php'; 
    include '../includes/connect_DB.php';

    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['username'];

    
    if (isset($_POST['game_id'])) {
        $game_id = $_POST['game_id'];

        $query = "INSERT INTO student_game_progress (student_id, game_id, complete)
                VALUES ('$user_id', '$game_id', 1)
                ON DUPLICATE KEY UPDATE complete = 1";
        mysqli_query($connect, $query) or die("Error: " . mysqli_error($connect));

        $xpQuery = "UPDATE student_level SET experience = experience + 50 WHERE student_id = '$user_id'";
        mysqli_query($connect, $xpQuery) or die("Error updating XP: " . mysqli_error($connect));

        $xp_check_stmt = $pdo->prepare("SELECT experience, level FROM student_level WHERE student_id = ?");
        $xp_check_stmt->execute([$user_id]);
        
        if ($xp_row = $xp_check_stmt->fetch(PDO::FETCH_ASSOC)) {
            $current_xp = (int)$xp_row['experience'];
            $current_level = (int)$xp_row['level'];
        
            $level = $current_level;
            $xp = $current_xp;
        
            while (true) {
                $xp_needed = 100 + ($level - 1) * 50;
        
                if ($xp >= $xp_needed) {
                    $xp -= $xp_needed;
                    $level++;
                } else {
                    break;
                }
            }
        
            if ($level != $current_level) {
                $stmt_update_lvl = $pdo->prepare("UPDATE student_level SET experience = ?, level = ? WHERE student_id = ?");
                $stmt_update_lvl->execute([$xp, $level, $user_id]);
            }
        }

        header("Location: Main_page.php");
        exit();
    }



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

    $class_result = [];
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
        $class_result[] = $class;
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
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill-rule="evenodd" clip-rule="evenodd" d="M23 4C23 3.11596 21.855 2.80151 21.0975 2.59348C21.0279 2.57437 20.9616 2.55615 20.8997 2.53848C19.9537 2.26818 18.6102 2 17 2C15.2762 2 13.8549 2.574 12.8789 3.13176C12.7296 3.21707 12.5726 3.33492 12.4307 3.44143C12.2433 3.58215 12.0823 3.70308 12 3.70308C11.9177 3.70308 11.7567 3.58215 11.5693 3.44143C11.4274 3.33492 11.2704 3.21707 11.1211 3.13176C10.1451 2.574 8.72378 2 7 2C5.38978 2 4.0463 2.26818 3.10028 2.53848C3.04079 2.55547 2.97705 2.57302 2.91016 2.59144C2.156 2.79911 1 3.11742 1 4V17C1 17.3466 1.17945 17.6684 1.47427 17.8507C1.94329 18.1405 2.56224 17.8868 3.11074 17.662C3.30209 17.5835 3.48487 17.5086 3.64972 17.4615C4.4537 17.2318 5.61022 17 7 17C8.2613 17 9.20554 17.4161 9.9134 17.8517C10.0952 17.9636 10.279 18.1063 10.4676 18.2527C10.9338 18.6148 11.4298 19 12 19C12.5718 19 13.0653 18.6162 13.5307 18.2543C13.7195 18.1074 13.9037 17.9642 14.0866 17.8517C14.7945 17.4161 15.7387 17 17 17C18.3898 17 19.5463 17.2318 20.3503 17.4615C20.5227 17.5108 20.7099 17.5898 20.9042 17.6719C21.4443 17.9 22.0393 18.1513 22.5257 17.8507C22.8205 17.6684 23 17.3466 23 17V4ZM3.33252 4.55749C3.13163 4.62161 3 4.81078 3 5.02166V14.8991C3 15.233 3.32089 15.4733 3.64547 15.3951C4.53577 15.1807 5.67777 15 7 15C8.76309 15 10.0794 15.5994 11 16.1721V5.45567C10.7989 5.29593 10.5037 5.08245 10.1289 4.86824C9.35493 4.426 8.27622 4 7 4C5.41509 4 4.12989 4.30297 3.33252 4.55749ZM17 15C15.2369 15 13.9206 15.5994 13 16.1721V5.45567C13.2011 5.29593 13.4963 5.08245 13.8711 4.86824C14.6451 4.426 15.7238 4 17 4C18.5849 4 19.8701 4.30297 20.6675 4.55749C20.8684 4.62161 21 4.81078 21 5.02166V14.8991C21 15.233 20.6791 15.4733 20.3545 15.3951C19.4642 15.1807 18.3222 15 17 15Z" fill="#FFFFFF"></path><path d="M2.08735 20.4087C1.86161 19.9047 2.08723 19.3131 2.59127 19.0873C3.05951 18.8792 3.54426 18.7043 4.0318 18.5478C4.84068 18.2883 5.95911 18 7 18C8.16689 18 9.16285 18.6289 9.88469 19.0847C9.92174 19.1081 9.95807 19.131 9.99366 19.1534C10.8347 19.6821 11.4004 20 12 20C12.5989 20 13.1612 19.6829 14.0012 19.1538C14.0357 19.1321 14.0708 19.1099 14.1066 19.0872C14.8291 18.6303 15.8257 18 17 18C18.0465 18 19.1647 18.2881 19.9732 18.548C20.6992 18.7814 21.2378 19.0122 21.3762 19.073C21.8822 19.2968 22.1443 19.8943 21.9118 20.4105C21.6867 20.9106 21.0859 21.1325 20.5874 20.9109C20.1883 20.7349 19.7761 20.5855 19.361 20.452C18.6142 20.2119 17.7324 20 17 20C16.4409 20 15.9037 20.3186 15.0069 20.8841C14.2635 21.3529 13.2373 22 12 22C10.7619 22 9.73236 21.3521 8.98685 20.8829C8.08824 20.3173 7.55225 20 7 20C6.27378 20 5.39222 20.2117 4.64287 20.4522C4.22538 20.5861 3.80974 20.7351 3.4085 20.9128C2.9045 21.1383 2.31305 20.9127 2.08735 20.4087Z" fill="#FFFFFF"></path></g></svg>
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
            <svg fill="#000000" viewBox="0 0 1024 1024" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M798.071 357.531c-16.527 24.259-51.62 24.259-68.147 0-9.185-13.476-9.185-32.01 0-45.486 16.527-24.259 51.62-24.259 68.147 0 9.185 13.476 9.185 32.01 0 45.486zm93.628 92.093c-16.527 24.259-51.62 24.259-68.147 0-9.185-13.476-9.185-32.01 0-45.486 16.527-24.259 51.62-24.259 68.147 0 9.185 13.476 9.185 32.01 0 45.486zm-189.305 0c-16.527 24.259-51.62 24.259-68.147 0-9.185-13.476-9.185-32.01 0-45.486 16.527-24.259 51.62-24.259 68.147 0 9.185 13.476 9.185 32.01 0 45.486zm95.677 95.164c-16.527 24.259-51.62 24.259-68.147 0-9.185-13.476-9.185-32.01 0-45.486 16.527-24.259 51.62-24.259 68.147 0 9.185 13.476 9.185 32.01 0 45.486zM360.192 428.417c0-53.017-42.983-96-96-96s-96 42.983-96 96 42.983 96 96 96 96-42.983 96-96zm40.96 0c0 75.638-61.322 136.96-136.96 136.96s-136.96-61.322-136.96-136.96 61.322-136.96 136.96-136.96 136.96 61.322 136.96 136.96z"/><path d="M983.038 727.533c-.352 61.995-50.737 112.151-112.843 112.151-39.998 0-76.347-20.949-96.661-54.546-5.852-9.679-18.443-12.782-28.122-6.929s-12.782 18.443-6.929 28.122c27.659 45.746 77.229 74.314 131.712 74.314 84.943 0 153.805-68.844 153.805-153.764l-1.254-19.506-40.634-281.277c-23.484-162.304-162.639-282.733-326.691-282.733H467.343c-11.311 0-20.48 9.169-20.48 20.48s9.169 20.48 20.48 20.48h188.078c143.699 0 265.584 105.483 286.153 247.638l40.355 278.923 1.109 16.649z"/><path d="M511.904 687.705c90.526 0 173.645 43.889 225.067 116.315 6.548 9.223 19.333 11.391 28.555 4.843s11.391-19.333 4.843-28.555c-59.025-83.133-154.528-133.562-258.465-133.562-11.311 0-20.48 9.169-20.48 20.48s9.169 20.48 20.48 20.48zM42.071 710.884l40.355-278.923c20.569-142.154 142.454-247.638 286.153-247.638h188.078c11.311 0 20.48-9.169 20.48-20.48s-9.169-20.48-20.48-20.48H368.579c-164.052 0-303.207 120.429-326.691 282.733L1.419 705.802.045 725.519C0 811.8 68.862 880.644 153.805 880.644c54.483 0 104.053-28.568 131.712-74.314 5.852-9.679 2.75-22.27-6.929-28.122s-22.27-2.75-28.122 6.929c-20.314 33.598-56.663 54.546-96.661 54.546-62.105 0-112.491-50.155-112.843-112.151l1.109-16.649z"/><path d="M512.096 646.745c-103.937 0-199.44 50.429-258.465 133.562-6.548 9.223-4.38 22.007 4.843 28.555s22.007 4.38 28.555-4.843c51.423-72.425 134.541-116.315 225.067-116.315 11.311 0 20.48-9.169 20.48-20.48s-9.169-20.48-20.48-20.48z"/></g></svg>
            <span>Mini Games</span>
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
        
        <div class="infocover">
            <hr id="linenav">
            <div class="user-info">
                <img src="<?php echo $fullPath; ?>" alt="Avatar" class="avatarnav">
                <div class="namenav">
                    <p><?php echo htmlspecialchars($user_name); ?></p>
                </div>
            </div>
        </div>

    </nav>
    <main>
        <?php include 'resheadstudent.php'; ?>

        <!--Loading animate-->
        <div id="loading-overlay" style="display: none;">
            <div class="spinner"></div>
        </div>

        <div class="mm">
        <!-- Teaching Notes Page -->
        <div class="container tab-content active" id="exercise">
            <div class="wrappernotes">

                <div class="header-row">
                <div class="header-row-top">
                    <h2>Notes</h2>
                    <div class="action-buttons">
                    <button class="export-btn" id="sort-btn" onclick="toggleSort()">⬆ Sort</button>
                    <button class="filter-btn" onclick="downloadSelected()">Download</button>
                    </div>
                </div>
                </div>

                <div class="table-box">
                <table>
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"/></th>
                        <th>Notes</th>
                        <th>Status</th>
                        <th>File Name</th>
                        <th>Description</th>
                        <th>Uploads Date</th>
                        <th>Download</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sort_order = isset($_GET['sort']) && $_GET['sort'] === 'desc' ? 'DESC' : 'ASC';
                        $toggle_order = $sort_order === 'ASC' ? 'desc' : 'asc';

                        $result = mysqli_query($connect, "SELECT class_id FROM student_class WHERE student_id = '$user_id'");
                        if ($row = mysqli_fetch_assoc($result)) {
                            $class_id = $row['class_id'];
                            $query = "SELECT title, description, file_name, create_time 
                                    FROM teacher_materials 
                                    WHERE class_id = '$class_id' 
                                    ORDER BY create_time $sort_order";
                            $materials_result = mysqli_query($connect, $query);
                            if (mysqli_num_rows($materials_result) > 0) {
                                while ($material = mysqli_fetch_assoc($materials_result)) {
                                    $file_path = "../phpfile/uploads_teacher/" . htmlspecialchars($material['file_name']);
                                    $upload_date = date("M d, h:i A", strtotime($material['create_time']));
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' /></td>";
                                    echo "<td>" . htmlspecialchars($material['title']) . "</td>";
                                    echo "<td class='status'>Available</td>";
                                    echo "<td>" . htmlspecialchars($material['file_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($material['description']) . "</td>";
                                    echo "<td>$upload_date</td>";
                                    echo "<td><a class='download-btn' href='$file_path' download>Download</a></td>";
                                    echo "<td class='ellipsis'>⋮</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No materials found for your class.</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Student not enrolled in any class.</td></tr>";
                        }
                    ?>

                    </tbody>
                </table>
                </div>
            </div>
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

            // ========== Not Yet Submitted ==========
            $sql_work = "SELECT availability_id, student_work, expire_date, lesson_id FROM class_work WHERE class_id = '$class_id'";
            $result_work = mysqli_query($connect, $sql_work);

            $pending_cards = '';
            $current_date = date('Y-m-d'); 

            if ($result_work && mysqli_num_rows($result_work) > 0) {
                while ($row = mysqli_fetch_assoc($result_work)) {
                    $availability_id = $row['availability_id'];
                    $lesson_id = $row['lesson_id'];

                    // Skip if already submitted
                    $check_submission = "SELECT 1 FROM student_submit WHERE student_id = '$user_id' AND lesson_id = '$lesson_id' AND class_id = '$class_id'";
                    $result_submission = mysqli_query($connect, $check_submission);
                    if (mysqli_num_rows($result_submission) > 0) continue;

                    $student_work = htmlspecialchars($row['student_work']);
                    $expire_date = htmlspecialchars($row['expire_date']);

                    // Get lesson title
                    $sql_lesson = "SELECT title FROM lessons WHERE file_name = '$student_work' LIMIT 1";
                    $result_lesson = mysqli_query($connect, $sql_lesson);
                    $lesson_title = $student_work;
                    if ($result_lesson && mysqli_num_rows($result_lesson) > 0) {
                        $lesson_row = mysqli_fetch_assoc($result_lesson);
                        $lesson_title = htmlspecialchars($lesson_row['title']);
                    }

                    $is_expired = strtotime($expire_date) < strtotime($current_date);

                    $button_html = $is_expired
                        ? '<button class="submit-button disabled" disabled>Deadline Passed</button>'
                        : '<a href="studentsubmit.php?availability_id=' . $row['availability_id'] . '" class="submit-button">Submit</a>';

                    $comment_html = '';
                    $sql_comments = "
                        SELECT c.message, c.created_at, t.T_Username
                        FROM content_comments c
                        JOIN teacher t ON c.sender_id = t.Teacher_ID
                        WHERE c.availability_id = '$availability_id'
                        AND c.sender_type = 'teacher'
                    ";
                    $result_comments = mysqli_query($connect, $sql_comments);
                    if ($result_comments && mysqli_num_rows($result_comments) > 0) {
                        while ($comment = mysqli_fetch_assoc($result_comments)) {
                            $username = htmlspecialchars($comment['T_Username']);
                            $message = htmlspecialchars($comment['message']);
                            $created_at = htmlspecialchars($comment['created_at']);

                            $comment_html .= '
                            <div class="author-time">' . $username . ' · ' . $created_at . '</div>
                            <div class="reply">' . $message . '</div>';
                        }
                    }

                    $reply_section = '';
                    if (!empty($comment_html)) {
                        $reply_section = '<div class="reply-section">' . $comment_html . '</div>';
                    }

                    $pending_cards .= '
                    <div class="announcement-card">
                        <div class="circle-image"></div>
                        <div class="author-time">Due: ' . $expire_date . '</div>
                        <div class="message-title">' . $lesson_title . '</div>
                        <div class="button-wrapper">' . $button_html . '</div>
                        ' . $reply_section . '
                    </div>';
                }
            }

            if (!empty($pending_cards)) {
                echo '
                <div class="section-divider"><span class="section-title">Not Yet Submitted</span></div>
                <div class="announcement-wrapper">' . $pending_cards . '</div>';
            }



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

                    $sql_lesson = "SELECT title FROM lessons WHERE file_name = '$student_work' LIMIT 1";
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
        <div class="container tab-content active" id="marked">
            <div class="flr">
                <?php
                $feedback_query = "
                    SELECT filename, score, description 
                    FROM student_submit
                    WHERE student_id = '$user_id' AND score IS NOT NULL
                    ORDER BY upload_time DESC
                ";

                $feedback_result = mysqli_query($connect, $feedback_query);
                $counter = 0;

                if ($feedback_result && mysqli_num_rows($feedback_result) > 0) {
                    while ($row = mysqli_fetch_assoc($feedback_result)) {
                        $filename = htmlspecialchars($row['filename']);
                        $file_path = "../Student/uploads/" . $filename;
                        $score = htmlspecialchars($row['score']);
                        $description = htmlspecialchars($row['description']);

                        $backgrounds = [
                            "linear-gradient(to bottom right, #ff5e62, #ff9966)",
                            "linear-gradient(to bottom right, #4a90e2, #5cd2e6)",
                            "linear-gradient(to bottom right, #f8b500, #fceabb)",
                            "linear-gradient(to bottom right, #6dd5ed, #2193b0)",
                            "linear-gradient(to bottom right, #ff758c, #ff7eb3)",
                            "linear-gradient(to bottom right, #43cea2, #185a9d)",
                        ];
                        $bg_style = $backgrounds[$counter % count($backgrounds)];
                        $counter++;

                        //star
                        function renderStars($score) {
                            $maxStars = 5;
                            $starCount = ($score / 100) * $maxStars;
                            $fullStars = floor($starCount);
                            $halfStar = ($starCount - $fullStars) >= 0.5;
                            $starsHtml = '';

                            for ($i = 0; $i < $fullStars; $i++) {
                                $starsHtml .= '<i class="fas fa-star"></i>';
                            }

                            if ($halfStar) {
                                $starsHtml .= '<i class="fas fa-star-half-alt"></i>';
                            }

                            $emptyStars = $maxStars - $fullStars - ($halfStar ? 1 : 0);
                            for ($i = 0; $i < $emptyStars; $i++) {
                                $starsHtml .= '<i class="far fa-star"></i>';
                            }

                            return '<span class="stars">' . $starsHtml . '</span>';
                        }

                        echo '
                        <div class="card project-card" style="background: ' . $bg_style . '">
                            <div class="lang-tag">Marked</div>
                            <div class="circle"></div>
                            <div class="tittle">Submitted File: <br><a href="' . $file_path . '" target="_blank">' . $filename . '</a></div>
                            <div class="rating">
                                Score: ' . $score . '% <br>' . renderStars($score) . '
                            </div>
                            <div class="comments" style="position: relative;">
                                <button class="read-more-btn" onclick="togglePopup(this)">read more..</button>
                                <div class="popup-description" style="display:none;">' . nl2br(htmlspecialchars($description)) . '</div>
                            </div>
                        </div>';
                        


                    }
                } else {
                    echo "<p>No marked homework available yet.</p>";
                }
                ?>
            </div>
        </div>

        <!--Quiz Page--> 
        <div class="container tab-content active" id="quiz">
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
                        10 => ['🫅', '💼', '🌟', '🥂', '🏛️', '💰', '🕊️'],
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

<?php
    $games = []; 
    $completedGames = [];

    $query = "SELECT * FROM mini_games";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $games[] = [
                'id' => $row['game_id'],
                'title' => htmlspecialchars($row['title']),
                'imagePath' => '../phpfile/uploads_mini_games/' . $row['image_name']
            ];
        }

        $user_id_escaped = mysqli_real_escape_string($connect, $user_id);
        $completedQuery = "SELECT game_id FROM student_game_progress WHERE student_id = '$user_id_escaped' AND complete = 1";
        $completedResult = mysqli_query($connect, $completedQuery);
        if ($completedResult) {
            while ($row = mysqli_fetch_assoc($completedResult)) {
                $completedGames[] = $row['game_id'];
            }
        }
?>
    <script>
        const completedGameIds = <?php echo json_encode($completedGames); ?>;
    </script>

    <div class="puzcover">
        <div class="outer-box">
            <div class="container1">
                <div class="puzzle-section">
                    <p class="label">Puzzle</p>
                    <div class="puzzle-box">
                        <button class="nav left" onclick="prevPuzzle()">&lt;</button>
                        <div class="main-image" id="mainImage">
                            <img class="base" id="baseImage" src="" alt="Puzzle" draggable="false">
                            <div class="slot slot-0" id="slot0" ondragover="allowDrop(event)" ondrop="drop(event, 0)"></div>
                            <div class="slot slot-1" id="slot1" ondragover="allowDrop(event)" ondrop="drop(event, 1)"></div>
                            <div class="slot slot-2" id="slot2" ondragover="allowDrop(event)" ondrop="drop(event, 2)"></div>
                            <div class="slot slot-3" id="slot3" ondragover="allowDrop(event)" ondrop="drop(event, 3)"></div>
                        </div>
                        <button class="nav right" onclick="nextPuzzle()">&gt;</button>
                    </div>
                    <div class="dots">
                        <?php for ($i = 0; $i < count($games); $i++): ?>
                            <span class="dot" id="dot<?= $i ?>"></span>
                        <?php endfor; ?>
                    </div>
                    <form id="submitForm" method="POST" style="display: none;">
                        <input type="hidden" name="game_id" id="hiddenGameId">
                        <button type="button" class="submit" onclick="submitPuzzle()">Submit</button>
                    </form>
                </div>
                <div class="pieces" id="piecesContainer"></div>
            </div>
        </div>
    </div>
<?php
    } else {
        echo "<p>No puzzle found.</p>";
    }
?>

        </div>
        
        <!--Profile Page-->
        <div class="container tab-content active" id="profile">
            <h1>Personal Profile</h1>
            <form action="../includes/change_Avatar.php" class="avatar_container" method="post" enctype="multipart/form-data">
            <input type="file" name="change_Avatar" id="change_Avatar" accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="this.form.submit()">
            <label for="change_Avatar" style="cursor: pointer;">
                <img src="<?php echo $fullPath; ?>" alt="Avatar" class="avatar">
                <span class="material-symbols-outlined" id="edit_avatar_icon">edit</span>
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
            <?php foreach ($class_result as $class): ?>
                <form action="../includes/process_enroll_class.php" class="enroll_form" method="post">
                
                    <input type="hidden" name="Max" value="<?php echo htmlspecialchars($class['Max']) ?>">
                    <input type="hidden" name="Current" value="<?php echo htmlspecialchars($class['Cur']) ?>">
                    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($user_id)?>">
                    <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class['class_id']) ?>">

                    <div class="enrollment_container">
                        <div>
                            <h4><?php echo htmlspecialchars($class['class_name']) ?></h4>
                            <p>Teach by: <?php 
                                $teacherNames = array();
                                foreach ($class['teachers'] as $teacher) {
                                    echo htmlspecialchars($teacher['T_Username']);
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
    //donwload macterial
    document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
    });

    function downloadSelected() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const checkbox = row.querySelector('input[type="checkbox"]');
        if (checkbox && checkbox.checked) {
        const downloadLink = row.querySelector('.download-btn');
        if (downloadLink) {
            const url = downloadLink.getAttribute('href');
            const a = document.createElement('a');
            a.href = url;
            a.download = '';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
        }
    });
    }
    //notes sort
    function toggleSort() {
    const urlParams = new URLSearchParams(window.location.search);
    const currentSort = urlParams.get('sort') === 'desc' ? 'desc' : 'asc';
    const nextSort = currentSort === 'asc' ? 'desc' : 'asc';
    window.location.href = window.location.pathname + '?sort=' + nextSort;
    }

    window.addEventListener('DOMContentLoaded', () => {
    const sortBtn = document.getElementById('sort-btn');
    const urlParams = new URLSearchParams(window.location.search);
    const currentSort = urlParams.get('sort');

    if (currentSort === 'desc') {
        sortBtn.innerHTML = '⬇ Sort';
    } else {
        sortBtn.innerHTML = '⬆ Sort';
    }
    });
    
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

    //sidebar and active content
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        const sidebarItems = document.querySelectorAll('#sidebar ul li');
        const tabContents = document.querySelectorAll('.tab-content');
        const overlay = document.getElementById('loading-overlay');

        let activeIndex = localStorage.getItem('activeTabIndex');
        activeIndex = activeIndex !== null ? parseInt(activeIndex, 10) : 0;

        sidebarLinks.forEach(link => link.classList.remove('active'));
        sidebarItems.forEach(item => item.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        sidebarLinks[activeIndex].classList.add('active');
        if (sidebarItems[activeIndex + 1]) {
            sidebarItems[activeIndex + 1].classList.add('active');
        }
        if (tabContents[activeIndex]) {
            tabContents[activeIndex].classList.add('active');
        }

        sidebarLinks.forEach((link, index) => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                if (overlay) overlay.style.display = 'flex';

                setTimeout(() => {
                    if (overlay) overlay.style.display = 'none';

                    sidebarLinks.forEach(link => link.classList.remove('active'));
                    sidebarItems.forEach(item => item.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    link.classList.add('active');
                    if (sidebarItems[index + 1]) {
                        sidebarItems[index + 1].classList.add('active');
                    }
                    if (tabContents[index]) {
                        tabContents[index].classList.add('active');
                    }

                    localStorage.setItem('activeTabIndex', index);
                }, 1000);
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
    
    //comment popout
    function togglePopup(button) {
        const popup = button.nextElementSibling;
        if (popup.style.display === "none" || popup.style.display === "") {
            popup.style.display = "block";
            button.textContent = "close";
        } else {
            popup.style.display = "none";
            button.textContent = "read more....";
        }
    }



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

    // Convert PHP games array to JavaScript
    const puzzleImages = <?php echo json_encode(array_column($games, 'imagePath')); ?>;
    const gameTitles = <?php echo json_encode(array_column($games, 'title')); ?>;
    const gameIds = <?php echo json_encode(array_column($games, 'id')); ?>;
    let currentPuzzle = 0;
    let placed = [null, null, null, null];

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        const pieceIndex = parseInt(ev.target.dataset.index);
        ev.dataTransfer.effectAllowed = 'move';
        ev.dataTransfer.setData("text", pieceIndex);
        const dragPreview = document.createElement('div');
        dragPreview.style.width = '120px';
        dragPreview.style.height = '67.5px';
        dragPreview.style.overflow = 'hidden';
        dragPreview.style.position = 'absolute';
        dragPreview.style.pointerEvents = 'none';
        dragPreview.style.top = '-9999px';
        const img = document.createElement('img');
        img.src = puzzleImages[currentPuzzle];
        img.style.width = '240px';
        img.style.height = '135px';
        switch(pieceIndex) {
            case 0: img.style.marginLeft = '0'; img.style.marginTop = '0'; break;
            case 1: img.style.marginLeft = '-120px'; img.style.marginTop = '0'; break;
            case 2: img.style.marginLeft = '0'; img.style.marginTop = '-67.5px'; break;
            case 3: img.style.marginLeft = '-120px'; img.style.marginTop = '-67.5px'; break;
        }
        dragPreview.appendChild(img);
        document.body.appendChild(dragPreview);
        ev.dataTransfer.setDragImage(dragPreview, 60, 33.75);
        setTimeout(() => document.body.removeChild(dragPreview), 0);
    }

    function drop(ev, slotIndex) {
        ev.preventDefault();
        const pieceIndex = ev.dataTransfer.getData("text");
        if (slotIndex != pieceIndex || placed[slotIndex]) return;
        const slot = document.getElementById(`slot${slotIndex}`);
        slot.innerHTML = "";
        const img = document.createElement("img");
        img.src = puzzleImages[currentPuzzle];
        img.className = "piece piece-" + slotIndex;
        slot.appendChild(img);
        placed[slotIndex] = true;
        const wrapper = document.getElementById(`piece-wrapper-${pieceIndex}`);
        if (wrapper) wrapper.classList.add("hidden");
        if (placed.every(Boolean)) {
            document.getElementById("mainImage").classList.add("fully-colored");
            document.querySelector('.label').textContent = gameTitles[currentPuzzle] + ' - Completed!';
            document.getElementById("submitForm").style.display = "block";
        }
    }

    function loadPuzzle(index) {
        const gameId = gameIds[index];
        const isCompleted = completedGameIds.includes(gameId);
        document.getElementById("baseImage").src = puzzleImages[index];
        document.getElementById("mainImage").classList.remove("fully-colored");
        document.querySelector('.label').textContent = gameTitles[index];
        for (let i = 0; i < 4; i++) {
            const slot = document.getElementById(`slot${i}`);
            slot.innerHTML = "";
            placed[i] = null;
            slot.ondragover = allowDrop;
            slot.ondrop = (ev) => drop(ev, i);
        }
        const container = document.getElementById("piecesContainer");
        container.innerHTML = "";
        if (isCompleted) {
            document.getElementById("mainImage").classList.add("fully-colored");
            document.querySelector('.label').textContent = gameTitles[index] + ' - Completed!';
            for (let i = 0; i < 4; i++) {
                const slot = document.getElementById(`slot${i}`);
                slot.innerHTML = "";
                const img = document.createElement("img");
                img.src = puzzleImages[index];
                img.className = "piece piece-" + i;
                slot.appendChild(img);
                slot.ondragover = null;
                slot.ondrop = null;
            }
            document.getElementById("submitForm").style.display = "none";
        } else {
            for (let i = 0; i < 4; i++) {
                const wrapper = document.createElement("div");
                wrapper.className = "piece-wrapper";
                wrapper.id = `piece-wrapper-${i}`;
                const piece = document.createElement("img");
                piece.src = puzzleImages[index];
                piece.className = `piece piece-${i}`;
                piece.setAttribute("draggable", true);
                piece.dataset.index = i;
                piece.ondragstart = drag;
                wrapper.appendChild(piece);
                container.appendChild(wrapper);
            }
            document.getElementById("submitForm").style.display = "none";
        }
        document.querySelectorAll('.dot').forEach((dot, i) => {
            dot.classList.toggle("active", i === index);
        });
    }

    function nextPuzzle() {
        currentPuzzle = (currentPuzzle + 1) % puzzleImages.length;
        loadPuzzle(currentPuzzle);
    }

    function prevPuzzle() {
        currentPuzzle = (currentPuzzle - 1 + puzzleImages.length) % puzzleImages.length;
        loadPuzzle(currentPuzzle);
    }

    function submitPuzzle() {
        if (placed.every(Boolean)) {
            document.getElementById("hiddenGameId").value = gameIds[currentPuzzle];
            document.getElementById("submitForm").submit();
        } else {
            alert("Please complete all pieces first.");
        }
    }

    loadPuzzle(0);



</script>

        